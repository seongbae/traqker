<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\MemberAdded;
use App\Mail\TaskAssigned;
use App\Mail\TaskComplete;
use App\Mail\TaskDue;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Mail;

class SendUserNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:notification {email} {taskid?}';

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
     * @return mixed
     */
    public function handle()
    {
        $email = $this->argument('email');
        $taskid = $this->argument('taskid');

        $user = User::where('email', $email)->first();
        $task = Task::find($taskid);

        if ($user && $task)
        {
            event(new TaskComplete($user, $task));
        }
        else
        {
            echo "No user or task found.\n";
        }
    }
}
