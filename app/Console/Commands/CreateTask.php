<?php

namespace App\Console\Commands;

use App\Events\TaskAssigned;
use App\Mail\TestMail;
use App\Models\ReceivedMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Task;

class CreateTask extends Command
{
    protected $signature = 'task:create';

    protected $description = 'Create task.';

    public function handle()
    {
        // Fire off an event, just randomly grabbing the first user for now
        $user = User::find(2);

        $task = Task::create(['name'=>'Test Task', 'user_id'=>2]);
        $task->users()->attach($user);

        event(new TaskAssigned([$user], $task));
    }
}
