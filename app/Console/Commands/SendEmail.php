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

class SendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:email {email}';

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
        $value = $this->argument('email');

        switch($value) {
            case "memberadded":
                $user = User::where('email', 'seong@lnidigital.com')->first();
                $project = Project::find(1);

                Mail::to($user)->send(new MemberAdded($project));
                break;
            case "taskassigned":
                $user = User::where('email', 'seong@lnidigital.com')->first();
                $task = Task::find(1);

                Mail::to($user)->send(new TaskAssigned($task));
                break;
            case "taskcomplete":
                $user = User::where('email', 'seong@lnidigital.com')->first();
                $task = Task::find(1);

                Mail::to($user)->send(new TaskComplete($task));
                break;
            case "taskdue":
                $user = User::where('email', 'seong@lnidigital.com')->first();
                $task = Task::find(1);

                Mail::to($user)->send(new TaskDue($task));
                break;
            default:
                break;
        }


    }
}
