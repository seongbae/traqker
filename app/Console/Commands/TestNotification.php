<?php

namespace App\Console\Commands;

use App\Events\InviteDeclined;
use Illuminate\Console\Command;
use App\Mail\MemberAdded;
use App\Mail\TaskAssigned;
use App\Mail\TaskComplete;
use App\Mail\TaskDue;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Mail;
use App\Events\InviteAccepted;
use App\Models\Team;
use App\Models\Invitation;

class TestNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:notification';

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
        $invite = Invitation::find(21);

        if ($invite)
        {
            event(new InviteDeclined($invite));
        }
    }
}
