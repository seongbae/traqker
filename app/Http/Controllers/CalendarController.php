<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Resources\CalendarResource;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\Task;

class CalendarController extends Controller
{
    public function index(Project $project)
    {
        if (\Illuminate\Support\Facades\Request::ajax()) {
            return CalendarResource::collection($project->tasks);
        }

        return view('projects.calendar');
    }

}
