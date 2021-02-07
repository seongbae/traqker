<?php

namespace App\Listeners;

use App\Events\AddedToProject;
use App\Events\MessageReceived;
use App\Notifications\MessageReceivedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\AddedToProjectNotification;
use Notification;
use App\Events\InviteAccepted;
use Illuminate\Support\Facades\Log;

class ChatMessageReceivedListener
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
    public function handle(MessageReceived $event)
    {
        foreach($event->getThread()->recipients as $user)
            Notification::send($user, new MessageReceivedNotification($event->getMessage()));
    }
}
