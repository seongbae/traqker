<?php

namespace App\Listeners;

use App\Events\AddedToProject;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\AddedToProjectNotification;
use Notification;
use App\Events\InviteAccepted;
use Illuminate\Support\Facades\Log;

class SendAddedToProjectNotification
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
    public function handle(AddedToProject $event)
    {
        foreach($event->getUsers() as $user)
            Notification::send($user, new AddedToProjectNotification($user, $event->getProject()));
    }
}
