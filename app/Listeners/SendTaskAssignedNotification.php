<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\TaskAssignedNotification;
use Notification;
use App\Events\TaskAssigned;
use Illuminate\Support\Facades\Log;

class SendTaskAssignedNotification
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
    public function handle(TaskAssigned $event)
    {
        Notification::send($event->getAssigned(), new TaskAssignedNotification($event->getOwner(), $event->getTask(), $event->getMessage()));
    }
}
