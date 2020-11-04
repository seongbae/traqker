<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\TaskUpdatedNotification;
use Notification;
use App\Events\TaskUpdated;
use Illuminate\Support\Facades\Log;

class SendTaskUpdatedNotification
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
    public function handle(TaskUpdated $event)
    {
        Notification::send($event->getTask()->usersToNotify($event->getUser()), new TaskUpdatedNotification($event->getUser(), $event->getTask(), $event->getMessage()));
    }
}
