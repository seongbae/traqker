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
use App\Events\TeamMemberAdded;
use App\Models\Team;

class SendTeamJoinedNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'team:join {userid} {teamid}';

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
        $user = User::find($this->argument('userid'));
        $team = Team::find($this->argument('teamid'));

        if ($user && $team)
        {
            event(new TeamMemberAdded($user, $team, $user, 'Hello world.  Thank you for joining traqker. '.$team->name));
        }
        else
        {
            echo "No user or team found.\n";
        }
    }
}
