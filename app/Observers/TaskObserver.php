<?php

namespace App\Observers;

use App\Models\Task;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskComplete;
use App\Events\TaskAssigned;
use App\Events\TaskComplete;
use Illuminate\Support\Facades\Log;
use Auth;
use Carbon\Carbon;

class TaskObserver
{
    /**
     * Handle the task "created" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function created(Task $task)
    {
        if ($task->assigned_to && $task->assigned_to != Auth::id())
            event(new TaskAssigned(Auth::user(), $task->assigned, $task, "New task assigned: ".$task->name));

        if ($task->project_id)
            $task->project->touch();
    }

    /**
     * Handle the task "updated" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function updated(Task $task)
    {
        if ($task->project_id)
            $task->project->touch();
    }

     /**
     * Listen to the User created event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updating(Task $task)
    {
      if($task->isDirty('status')){
        // status has changed
        if ($task->status == 'complete') {
            Mail::to($task->owner)->send(new TaskComplete($task));
        }

        event(new TaskComplete(Auth::user(), $task, "Task <strong>".$task->name."</strong> has been marked ".$task->status));
      }
    }

    /**
     * Handle the task "deleted" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function deleted(Task $task)
    {
        if ($task->project_id)
            $task->project->touch();
    }

    /**
     * Handle the task "restored" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function restored(Task $task)
    {
        //
    }

    /**
     * Handle the task "force deleted" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function forceDeleted(Task $task)
    {
        //
    }
}
