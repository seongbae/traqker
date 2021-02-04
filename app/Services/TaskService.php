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
use Log;

class TaskService
{
    public function createTask($name,
                               $description=null,
                               $dependencies=null,
                               $priority=null,
                               $projectId=null,
                               $assignees=null,
                               $startOn=null,
                               $dueOn=null,
                               $estimate=null,
                               $progress=null,
                               $files=null)
    {
        if ($priority == null)
            $priority = "medium";

        if ($progress == null) {
            $progress = 0;
        }

        if ($progress > 0 && $progress < 100)
            $status = 'active';
        elseif ($progress == 100)
            $status = 'complete';

        $task = Task::create(
            [
                'name'=>$name,
                'description'=>$description,
                'project_id'=>$projectId,
                'progress'=>$progress,
                'start_on' => $startOn,
                'due_on'=>$dueOn,
                'estimate'=>$estimate,
                'status'=>'created',
                'priority'=>$priority
            ]);

        if ($projectId && $assignees)
        {
            $changes = $task->users()->sync(explode(",", $assignees));

            if (count($changes['attached'])>0)
                event(new TaskAssigned(User::find($changes['attached']), $task));
        }
        else
            $task->users()->attach(Auth::id());

        if ($dependencies)
            $task->tasks()->sync(explode(',', $dependencies));
        else
            $task->tasks()->detach();

        return $task;

    }

    public function updateTask($task,
                               $name,
                               $description,
                               $priority,
                               $projectId,
                               $startOn,
                               $dueOn,
                               $estimate,
                               $progress,
                               $assignees,
                               $dependencies
                               )
    {

        if ($progress)
        {
            if ($progress > 0 && $progress < 100)
                $task->status = 'active';
            elseif ($progress == 100) {
                $task->status = 'complete';
                $task->completed_on = Carbon::now()->toDateTimeString();
            }

            $task->progress = $progress;
        }

        if ($name)
            $task->name = $name;

        if ($description)
            $task->description = $description;

        if ($priority)
            $task->priority = $priority;

        if ($projectId) {
            if ($projectId != $task->project_id)
                $task->section_id = null;

            $task->project_id = $projectId;
        }

        if ($startOn)
            $task->start_on = $startOn;

        if ($dueOn)
            $task->due_on = $dueOn;

        if ($estimate)
            $task->estimate_hour = $estimate;


        $task->save();

        // If null, only partial update. do not make any changes.
        if (!empty($assignees))
        {
            $changes = $task->users()->sync(explode(",", $assignees));

            if (count($changes['attached'])>0)
                event(new TaskAssigned(User::find($changes['attached']), $task));
        }
        elseif ($assignees === "") {
            // partial update. do not detach users.
        }
        elseif ($assignees == null)
            $task->users()->detach();

        if ($dependencies == "")
            $task->tasks()->detach();
        elseif ($dependencies != null)
            $task->tasks()->sync(explode(',', $dependencies));

        return $task;
    }

    public function updateOrders($taskIds)
    {
        $order = 1;
        $placeholders = implode(',',array_fill(0, count($taskIds), '?'));
        $tasks = Task::whereIn('id', $taskIds)->orderByRaw("field(id,{$placeholders})", $taskIds)->get();

        foreach($tasks as $task)
        {
            $task->order = $order;
            $task->save();
            $order++;
        }
    }

    public function updateStatus($task, $status, $hours=null)
    {
        $task->status = $status;

        if ($task->status == 'complete')
            $task->completed_on = Carbon::now()->toDateTimeString();

        $task->save();

        if ($hours)
        {
            Hour::create([
                'hours'=>$hours,
                'description'=>$task->name,
                'worked_on'=>Carbon::now()->toDateTimeString(),
                'task_id'=>$task->id,
                'project_id'=>$task->project->id
            ]);
        }

        return $task;
    }


}
