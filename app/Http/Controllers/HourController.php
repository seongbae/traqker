<?php

namespace App\Http\Controllers;

use App\Models\Hour;
use App\Http\Datatables\HourDatatable;
use App\Http\Requests\HourRequest;
use Illuminate\Http\Request;
use App\Models\Project;
use Auth;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class HourController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $userHours = array();


        $dateRange = strip_tags($request->get('reportrange'));
        $projectIds = $request->get('projects');
        $dates = array();

        if ($dateRange)
        {
            $dates = explode(' - ', $dateRange);
            $startDate = Carbon::parse($dates[0]);
            $endDate = Carbon::parse($dates[1].' 11:59:59');

            $userHours = array();

            $projects = Project::whereIn('id', $projectIds)->get();

            $users = [];
            foreach($projects as $project)
            {
                if (Auth::id() == $project->user_id) {
                    foreach ($project->members as $user) {
                        if (!in_array($user, $users))
                            $users[] = $user;
                    }
                } else {
                    foreach ($project->members as $user) {
                        if (!in_array($user, $users) && $user->id == Auth::id())
                            $users[] = $user;
                    }
                }
            }

            foreach($users as $user)
            {
                $totalHours = 0;

                $hoursSet = $user->hours()->whereBetween('worked_on', [$startDate, $endDate])->whereIn('project_id', $projectIds)->get();

                foreach($hoursSet as $hour)
                {
                    $totalHours += $hour->hours;
                }

                $userHours[$user->id] = array('user'=>$user, 'hours'=>$hoursSet, 'total_hours'=>$totalHours);
            }

        }
        else
        {
            $start = Carbon::now()->subDays(30)->format('m/d/Y');
            $end = Carbon::now()->format('m/d/Y');
            $dates[0] = $start;
            $dates[1] = $end;
        }

        return view('hours.index',compact('userHours'))->with('start', $dates[0])->with('end', $dates[1]);

//        $query = Hour::with('project')->where('user_id', Auth::id());
//        $datatables = HourDatatable::make($query);
//
//        return $request->ajax()
//            ? $datatables->json()
//            : view('hours.index', $datatables->html());
    }

    public function create()
    {
        $projects = Project::where('user_id', Auth::id())->pluck('id','name')->toArray();

        return view('hours.create')
                ->with('projects', $projects);;
    }

    public function store(HourRequest $request)
    {
        if ($request->get('task_id'))
        {
            $task = Task::withoutGlobalScopes()->find($request->get('task_id'));

            Hour::create(array_merge($request->all(), [
                'description'=>$task->name,
                'worked_on'=>$request->get('date'),
                'task_id'=>$task->id,
                'project_id'=> $task->project->id
            ]));

            if ($task && $request->get('mark_as_complete'))
            {
                $task->status = 'complete';
                $task->completed_on = $request->get('date');
                $task->save();
            }
            return redirect()->back();
        }
        else
        {
            $hour = Hour::create($request->all());

            $task = Task::create([
                'name'=>$request->get('description')!=null ? $request->get('description') : 'Completed '.$request->get('hours').' hours of work',
                'assigned_to'=>Auth::id(),
                'completed_on'=>Carbon::now()->toDateTimeString(),
                'status'=>'complete',
                'project_id'=>$request->get('project_id'),
                'user_id'=>Auth::id()
            ]);

            $hour->task_id = $task->id;
            $hour->save();
        }

        return $request->input('submit') == 'reload'
            ? redirect()->route('hours.create')
            : redirect()->route('hours.index');
    }

    public function show(Hour $hour)
    {
        return view('hours.show', compact('hour'));
    }

    public function edit(Hour $hour)
    {
        $projects = Project::where('user_id', Auth::id())->pluck('id','name')->toArray();

        return view('hours.edit', compact('hour','projects'));
    }

    public function update(HourRequest $request, Hour $hour)
    {
        $hour->update($request->all());

        return $request->input('submit') == 'reload'
            ? redirect()->route('hours.edit', $hour->id)
            : redirect()->route('hours.index');
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function destroy(Hour $hour)
    {
        $hour->delete();

        return redirect()->route('hours.index');
    }

    public function deleteHour(Hour $hour)
    {
        $hour->delete();

        return redirect()->back();
    }
}
