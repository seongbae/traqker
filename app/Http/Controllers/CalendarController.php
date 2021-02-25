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
        $project = Project::findOrFail($id);

        return CalendarResource::collection($project->tasks);
    }

    public function indexTeam($id)
    {
        $tasks = Task::findOrFail($id);

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
