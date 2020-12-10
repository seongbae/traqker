<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Resources\CalendarResource;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\Task;
use Auth;

class CalendarController extends Controller
{
    public function indexProject($id)
    {
        $project = Project::find($id);

        return CalendarResource::collection($project->tasks);
    }

    public function indexTeam(Project $project)
    {
        if (\Illuminate\Support\Facades\Request::ajax()) {
            return CalendarResource::collection($project->tasks);
        }

        return view('projects.calendar');
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
