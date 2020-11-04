<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\NewTeamMemberNotification;
use Notification;
use App\Events\TeamMemberAdded;
use Illuminate\Support\Facades\Log;

class SendNewTeamMemberNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(TeamMemberAdded $event)
    {
        $event->getUser()->notify(new NewTeamMemberNotification($event->getCauser(), $event->getTeam(), $event->getMessage()));
    }
}
