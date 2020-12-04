<?php

namespace App\Listeners;

use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\InviteAcceptedNotification;
use Notification;
use App\Events\InviteAccepted;
use Illuminate\Support\Facades\Log;

class SendInviteAcceptedNotification
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
    public function handle(InviteAccepted $event)
    {
        $invite = $event->getInvite();
        $invite->fromUser->notify(new InviteAcceptedNotification($event->getInvite()));
    }
}
