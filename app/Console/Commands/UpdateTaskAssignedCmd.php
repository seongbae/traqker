<?php

namespace App\Console\Commands;

use App\Mail\TestMail;
use App\Models\Project;
use App\Models\ReceivedMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Task;

class UpdateTaskAssignedCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:taskassigned';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tasks = Task::all();

        foreach($tasks as $task)
        {
            if ($task->assigned_to)
            {
                $task->users()->attach($task->assigned_to);
                $task->save();
            }

        }
    }

}
