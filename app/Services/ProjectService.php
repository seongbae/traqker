<?php


namespace App\Services;


use App\Events\TaskAssigned;
use App\Events\TaskComplete;
use App\Models\Hour;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Auth;

class ProjectService
{
    public function archive($project)
    {
        $project->archived = 1;
        $project->save();
    }

    public function unarchive($project)
    {
        $project->archived = 0;
        $project->save();
    }


}
