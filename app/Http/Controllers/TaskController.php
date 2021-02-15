<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskIDNameResource;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Http\Datatables\TaskDatatable;
use App\Http\Requests\TaskRequest;
use App\Scopes\CompletedScope;
use App\Services\TaskService;
use Illuminate\Http\Request;
use App\Models\Project;
use Auth;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Hour;
use Illuminate\Support\Facades\Mail;
use App\Scopes\ArchiveScope;
use App\Http\Resources\MemberResource;
use App\Events\TaskAssigned;
use App\Events\SendReminderEmail;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($offset=0, $limit=10, Request $request)
    {
        $query = Auth::user()->tasks()->orderBy('created_at','desc')->get();

        $datatables = TaskDatatable::make($query);

         if( $request->is('api/*')) {
            return TaskResource::collection($query->skip($offset)->take($limit));
        }
        elseif ($request->ajax()) {
            return $datatables->json();
        } else {
            return view('tasks.index', $datatables->html());
        }
    }

    public function indexArchived(Request $request)
    {
        $query = Auth::user()->tasks()->withoutGlobalScope(ArchiveScope::class)->where('archived', true);

        $datatables = TaskDatatable::make($query);

        return $request->ajax()
            ? $datatables->json()
            : view('tasks.index', $datatables->html());
    }

    public function indexDeleted(Request $request, Project $project)
    {
        if (empty($project))
            $query = $project->tasks()->withTrashed()->whereNotNull('deleted_at');
        else
            $query = Auth::user()->tasks()->withTrashed()->whereNotNull('deleted_at');

        $datatables = TaskDatatable::make($query);

        return $request->ajax()
            ? $datatables->json()
            : view('tasks.index', $datatables->html());
    }

    public function indexCompleted(Request $request, Project $project)
    {
        if (empty($project))
            $query = Auth::user()->tasks()->withoutGlobalScope(CompletedScope::class)->where('status', 'complete')->select('tasks.*');
        else
            $query = $project->tasks()->withoutGlobalScope(CompletedScope::class)->where('status', 'complete')->select('tasks.*');

        $datatables = TaskDatatable::make($query);

        return $request->ajax()
            ? $datatables->json()
            : view('tasks.index', $datatables->html());
    }

    public function create()
    {
        $users = [];
        $assignees = [];
        $tasks = [];
        $dependencies = [];

        if (app('request')->input('project'))
        {
            $project = Project::find(app('request')->input('project'));

            if ($project)
                $users = MemberResource::collection($project->members);

            $tasks = TaskIDNameResource::collection($project->tasks);
        }
        else
        {
            foreach(Auth::user()->projects as $project)
                foreach($project->members as $member)
                    if (!array_key_exists($member->id, $users))
                        $users[] = array('value'=>$member->id, 'text'=>$member->name);
        }

        if (app('request')->input('type') == 'milestone')
            $milestone = 1;
        else
            $milestone = 0;

        $priority = ['high','medium','low'];

        return view('tasks.create')
                ->with('projects', Auth::user()->projects()->orderBy('projects.name')->pluck('projects.id','projects.name')->toArray())
                ->with('users', $users)
                ->with('task', null)
                ->with('priority', $priority)
                ->with('milestone', $milestone)
                ->with('tasks', $tasks)
                ->with('dependencies', $dependencies)
                ->with('assignees', $assignees);
    }

    public function store(TaskRequest $request, TaskService $taskService)
    {
        $task = $taskService->createTask(
            $request->name,
            $request->description,
            $request->dependencies,
            $request->priority,
            $request->project_id,
            $request->assignees,
            $request->start_on,
            $request->due_on,
            $request->estimate,
            $request->progress,
            $request->file('files')
        );

        if( $request->is('api/*') || $request->ajax())
            return $request->json(new TaskResource($task), 200);

        if ($request->redirect_to == 'project')
        {
            $redirectToProject = route('projects.show', ['project'=>$task->project]);
            $redirectToCreate = route('tasks.create').'?project='.$request->project_id.'&redirect_to=project';
        }
        else
        {
            $redirectToProject = route('tasks.index');
            $redirectToCreate = route('tasks.create');
        }

        return $request->input('submit') == 'reload'
            ? redirect()->to($redirectToCreate)
            : redirect()->to($redirectToProject);
    }

    public function show(Request $request, Task $task)
    {
        $this->authorize('view', $task);

        if( $request->is('api/*') || $request->ajax())
            return response()->json(new TaskResource($task), 200);
        else
            return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        $projects = Auth::user()->projects()->orderBy('projects.name')->pluck('projects.id','projects.name')->toArray();

        $priority = ['high','medium','low'];

        $users = [];
        if ($task->project_id)
            $users = MemberResource::collection($task->project->members);
        else
            $users[] = array('value'=>Auth::id(), 'text'=>Auth::user()->name);

        $tasks = [];
        if ($task->project_id)
            $tasks = TaskIDNameResource::collection($task->project->tasks);

        $assignees = MemberResource::collection($task->users);
        $dependencies = TaskIDNameResource::collection($task->tasks);

        return view('tasks.edit', compact('task', 'projects', 'users', 'priority', 'assignees','tasks', 'dependencies'));
    }

    public function update(TaskRequest $request, Task $task, TaskService $taskService)
    {
        $this->authorize('update', $task);

        $task = $taskService->updateTask(
            $task,
            $request->name,
            $request->description,
            $request->priority,
            $request->project_id,
            $request->start_on,
            $request->due_on,
            $request->estimate_hour,
            $request->progress,
            $request->has('assignees') ? $request->assignees : "", // null = detach. "" = no change.
            $request->dependencies
        );

        if ($request->has('files'))
            $task->attachFiles($request->has('files'));

        if( $request->is('api/*') || $request->ajax())
            return response()->json(new TaskResource($task), 200);

        return $request->input('submit') == 'reload'
            ? redirect()->route('tasks.edit', $task->id)
            : redirect()->route('tasks.show', $task->id);
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function destroy(Task $task, Request $request)
    {
        $this->authorize('delete', $task);

        if ($task->project_id)
            $redirectTo = route('projects.show', ['project'=>$task->project]);
        else
            $redirectTo = route('tasks.index');

        $task->delete();

        if( $request->is('api/*') || $request->ajax())
            return response()->json(['success'], 200);

        return redirect()->to($redirectTo);
    }

    public function updateOrders(Request $request, Task $task, TaskService $taskService)
    {
        $this->authorize('update', $task);

        $task = $taskService->updateOrders($request->orders);

        if( $request->is('api/*') || $request->ajax())
            return $request->json([], 200);
        else
            return redirect()->back();
    }

    public function updateStatus(Request $request, Task $task, TaskService $taskService)
    {
        $this->authorize('update', $task);

        $task = $taskService->updateStatus($task, $request->status, $request->hours);

        if( $request->is('api/*') || $request->ajax())
            return response()->json(new TaskResource($task), 200);

        return redirect()->back();
    }

    public function updateList(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $lines = explode("\n", $task->description);

        if ($request->value == 'true')
            $checkboxValue = preg_replace("/\[]/", "[*]", $lines[$request->lineno]);
        else
            $checkboxValue = preg_replace("/\[\*]/", "[]", $lines[$request->lineno]);

        $task->description = str_replace($lines[$request->lineno], $checkboxValue, $task->description);
        $task->save();

        if ($request->ajax())
            return response()->json(['success'], 200);

        return redirect()->back();
    }

    public function archiveTask(Task $task)
    {
        $this->authorize('update', $task);

        $task->archived = true;
        $task->save();

        return redirect()->route('tasks.index');
    }

    public function unarchiveTask(Task $task)
    {
        $this->authorize('update', $task);

        $task->archived = false;
        $task->save();

        return redirect()->back();
    }
}
