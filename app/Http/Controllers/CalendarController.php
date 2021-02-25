<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Resources\CalendarResource;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Team;
use Auth;
use Log;

class CalendarController extends Controller
{
    public function indexProject($id)
    {
        $project = Project::findOrFail($id);

        return CalendarResource::collection($project->tasks);
    }

    public function indexTeam($id)
    {
        $team = Team::findOrFail($id);
        $tasks = Task::whereIn('project_id', $team->projects->pluck('id')->toArray())->get();

        return CalendarResource::collection($tasks);
    }

    public function indexUser()
    {
        $tasks = Auth::user()->tasks;

        return CalendarResource::collection($tasks);
    }

    public function index()
    {
        return view('calendar.index');
    }

}
