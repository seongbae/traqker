<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\TaskCompleteNotification;
use Notification;
use App\Events\TaskComplete;
use Illuminate\Support\Facades\Log;

class SendTaskCompleteNotification
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
    public function handle(TaskComplete $event)
    {
        foreach($event->getTask()->getAllRelatedUsersExcept($event->getUser()) as $user)
        {
            Notification::send($user, new TaskCompleteNotification($user, $event->getTask()));
        }

    }
}
