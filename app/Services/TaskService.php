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
        if ($progress > 0 && $progress < 100)
            $task->status = 'active';
        elseif ($progress == 100) {
            $task->status = 'complete';
            $task->completed_on = Carbon::now()->toDateTimeString();
        }

        if ($task->status == 'complete')
            $task->progress = 100;
        else
            $task->progress = $progress;

        $task->name = $name;
        $task->description = $description;
        $task->priority = $priority;
        $task->project_id = $projectId;
        $task->start_on = $startOn;
        $task->due_on = $dueOn;
        $task->estimate_hour = $estimate;
        $task->save();

        if ($assignees)
        {
            $changes = $task->users()->sync(explode(",", $assignees));

            if (count($changes['attached'])>0)
                event(new TaskAssigned(User::find($changes['attached']), $task));
        }
        else
            $task->users()->detach();

        if ($dependencies)
            $task->tasks()->sync(explode(',', $dependencies));
        else
            $task->tasks()->detach();

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
    }


}
