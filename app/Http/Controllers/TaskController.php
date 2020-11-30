<?php

namespace App\Http\Controllers;

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

        return $request->ajax()
            ? $datatables->json()
            : view('tasks.index', $datatables->html());
    }

    public function indexArchived(Request $request)
    {
        $query = Auth::user()->tasks()->withoutGlobalScope(ArchiveScope::class)->where('archived', true);

        $datatables = TaskDatatable::make($query);

        return $request->ajax()
            ? $datatables->json()
            : view('tasks.index', $datatables->html());
    }

    public function indexDeleted(Request $request)
    {
        $query = Auth::user()->tasks()->withTrashed()->whereNotNull('deleted_at');

        $datatables = TaskDatatable::make($query);

        return $request->ajax()
            ? $datatables->json()
            : view('tasks.index', $datatables->html());
    }

    public function indexCompleted(Request $request)
    {
        $query = Auth::user()->tasks()->withoutGlobalScope(CompletedScope::class)->where('status', 'complete')->select('tasks.*');

        $datatables = TaskDatatable::make($query);

        return $request->ajax()
            ? $datatables->json()
            : view('tasks.index', $datatables->html());
    }

    public function create()
    {
        $projects = Project::where('user_id', Auth::id())->get();

        $users = [];
        foreach($projects as $project)
            foreach($project->members as $member)
                if (!array_key_exists($member->id, $users))
                    $users[$member->id] = $member->name;

        if (app('request')->input('type') == 'milestone')
            $milestone = 1;
        else
            $milestone = 0;

        $priority = ['high','medium','low'];

        return view('tasks.create')
                ->with('projects', $projects->pluck('id','name')->toArray())
                ->with('users', array_flip($users))
                ->with('task', null)
                ->with('priority', $priority)
                ->with('milestone', $milestone);
    }

    public function store(TaskRequest $request)
    {
        if ($request->priority)
            $priority = $request->priority;
        else
            $priority = "medium";

        $task = Task::create(array_merge($request->all(),['user_id'=>Auth::id(),'status'=>'created','priority'=>$priority]));

        $task->users()->attach(Auth::id());

        if ($request->ajax())
            return $request->json([], 200);

        if ($request->redirect_to == 'project')
            $redirectTo = route('projects.show', ['project'=>$task->project]);
        else
            $redirectTo = route('tasks.index');

        return $request->input('submit') == 'reload'
            ? redirect()->route('tasks.create')
            : redirect()->to($redirectTo);
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);

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

        $assignees = MemberResource::collection($task->users);

        return view('tasks.edit', compact('task', 'projects', 'users', 'priority', 'assignees'));
    }

    public function update(TaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $task->update($request->all());

        if ($request->assignees)
            $task->users()->syncWithoutDetaching($request->assignees);
        else
            $task->users()->detach();

        if ($request->orders)
        {
            Log::info('orders:'.json_encode($request->orders));

            $order = 1;
            $placeholders = implode(',',array_fill(0, count($request->orders), '?'));
            $tasks = Task::whereIn('id', $request->orders)->orderByRaw("field(id,{$placeholders})", $request->orders)->get();

            foreach($tasks as $task)
            {
                Log::info('task->id:'.$task->id.' order:'.$order);
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

        if ($request->ajax())
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
