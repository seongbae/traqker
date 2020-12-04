<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Notification;
use App\Events\InviteCreated;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserInvitation;
use App\Notifications\UserInvitedNotification;

class HandleInviteCreated
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
    public function handle(InviteCreated $event)
    {
        if ($event->getInvitation()->to_user_id)
            $event->getInvitation()->toUser->notify(new UserInvitedNotification($event->getInvitation()));
        else
            Mail::to($event->getInvitation()->email)->send(new UserInvitation($event->getInvitation()));
    }
}
