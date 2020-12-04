<?php

namespace App\Listeners;

use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\InviteDeclinedNotification;
use Notification;
use App\Events\InviteDeclined;
use Illuminate\Support\Facades\Log;

class SendInviteDeclinedNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(InviteDeclined $event)
    {
        $invite = $event->getInvite();
        $invite->fromUser->notify(new InviteDeclinedNotification($event->getInvite()));
    }
}
