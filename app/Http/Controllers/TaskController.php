<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Datatables\TaskDatatable;
use App\Http\Requests\TaskRequest;
use Illuminate\Http\Request;
use App\Models\Project;
use Auth;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Hour;
use Illuminate\Support\Facades\Mail;
use App\Scopes\ArchiveScope;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $status = $request->get('status');
        $project = $request->get('project');
        $deleted = $request->get('deleted');
        $archived = $request->get('archived');

        if ($status)
            $query = Task::with('assigned')->with('project')
                ->where('status', $status)
                ->where(function($query) {
                    $query->where('user_id', Auth::id())->orWhere('assigned_to', Auth::id());
                });
        else if ($project)
            $query = Task::with('assigned')->with('project')
                ->where('project_id', $project)
                ->where(function($query) {
                    $query->where('user_id', Auth::id())->orWhere('assigned_to', Auth::id());
                });
        else if ($deleted == 1)
            $query = Task::withTrashed()->with('assigned')->with('project')->where('user_id', Auth::id())->whereNotNull('deleted_at');
        else if ($archived == 1)
            $query = Task::with('assigned')->with('project')->withoutGlobalScope(ArchiveScope::class)
                ->where('archived', true)
                ->where(function($query) {
                    $query->where('user_id', Auth::id())->orWhere('assigned_to', Auth::id());
                });
        else
            $query = Task::with('assigned')->with('project')->where('user_id', Auth::id())->where('status','!=','complete');

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
        if ($request->assigned_to)
            $assignedTo = $request->assigned_to;
        else
            $assignedTo = Auth::id();

        if ($request->priority)
            $priority = $request->priority;
        else
            $priority = "medium";

        $task = Task::create(array_merge($request->all(),['user_id'=>Auth::id(),'status'=>'created','priority'=>$priority, 'assigned_to'=>$assignedTo]));

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
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $projects = Project::where('user_id', Auth::id())->pluck('id','name')->toArray();

        $priority = ['high','medium','low'];

        $users = User::all()->pluck('id','name')->toArray();

        return view('tasks.edit', compact('task', 'projects', 'users', 'priority'));
    }

    public function update(TaskRequest $request, Task $task)
    {
        $task->update($request->all());

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
        $task->delete();

        if (\Illuminate\Support\Facades\Request::ajax())
            return response()->json(['success'], 200);

        return redirect()->route('tasks.index');
    }

    public function updateStatus(Request $request, Task $task)
    {
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
        $task->archived = true;
        $task->save();

        return redirect()->route('tasks.index');
    }

    public function unarchiveTask(Task $task)
    {
        $task->archived = false;
        $task->save();

        return redirect()->back();
    }
}
