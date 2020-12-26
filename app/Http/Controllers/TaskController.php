<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskIDNameResource;
use App\Models\Task;
use App\Http\Datatables\TaskDatatable;
use App\Http\Requests\TaskRequest;
use App\Scopes\CompletedScope;
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

    public function index(Request $request)
    {
        $query = Auth::user()->tasks;

        $datatables = TaskDatatable::make($query);

        if( $request->is('api/*') || $request->ajax())
            return $datatables->json();
        else
            return view('tasks.index', $datatables->html());
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
                ->with('assignees', $assignees);
    }

    public function store(TaskRequest $request)
    {
        if ($request->priority)
            $priority = $request->priority;
        else
            $priority = "medium";

        $task = Task::create(array_merge($request->all(),['user_id'=>Auth::id(),'status'=>'created','priority'=>$priority]));

        if ($request->project_id && $request->assignees)
        {
            $changes = $task->users()->sync(explode(",", $request->assignees));

            if (count($changes['attached'])>0)
                event(new TaskAssigned(User::find($changes['attached']), $task));
        }
        else
            $task->users()->attach(Auth::id());

        if ($request->file('files')) {
            $files = $request->file('files');
            foreach ($files as $file) {
                $task->addFile($file);
            }
        }

        if( $request->is('api/*') || $request->ajax())
            return $request->json($task, 200);

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
            return $request->json($task, 200);
        else
            return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        $projects = Project::where('user_id', Auth::id())->pluck('id','name')->toArray();

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

    public function update(TaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $task->update($request->all());

        if ($request->has('assignees')) {
            if ($request->assignees == null)
                $task->users()->detach();
            else {
                $changes = $task->users()->sync(explode(",", $request->assignees));

                if (count($changes['attached'])>0)
                    event(new TaskAssigned(User::find($changes['attached']), $task));
            }
        }

        if ($request->has('dependencies')) {
            if ($request->dependencies == null)
                $task->tasks()->detach();
            else {
                $task->tasks()->sync(explode(',', $request->dependencies));
            }
        }

        if ($request->orders)
        {
            $order = 1;
            $placeholders = implode(',',array_fill(0, count($request->orders), '?'));
            $tasks = Task::whereIn('id', $request->orders)->orderByRaw("field(id,{$placeholders})", $request->orders)->get();

            foreach($tasks as $task)
            {
                $task->order = $order;
                $task->save();
                $order++;
            }
        }

        if ($request->file('files')) {
            $files = $request->file('files');
            foreach ($files as $file) {
                $task->addFile($file);
            }
        }

        if( $request->is('api/*') || $request->ajax())
            return $request->json([], 200);

        return $request->input('submit') == 'reload'
            ? redirect()->route('tasks.edit', $task->id)
            : redirect()->route('tasks.show', $task->id);
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        if (\Illuminate\Support\Facades\Request::ajax())
            return response()->json(['success'], 200);

        return redirect()->route('tasks.index');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $task->status = $request->get('status');

        if ($task->status == 'complete')
        {
            $task->completed_on = Carbon::now()->toDateTimeString();
        }

        $task->save();

        if ($request->get('hours'))
        {
            Hour::create(array_merge($request->all(), [
                'description'=>$task->name,
                'worked_on'=>Carbon::now()->toDateTimeString(),
                'task_id'=>$task->id,
                'project_id'=>$task->project->id
            ]));
        }

        if ($request->ajax())
            return response()->json(['success'], 200);

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
