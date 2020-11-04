<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Notification;
use App\Events\UserInvited;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserInvitation;

class SendInviteNotification
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
    public function handle(UserInvited $event)
    {
//        $user = (new User)->forceFill([
//            'name' => 'Name',
//            'email' => $event->getInvitation()->email,
//        ]);

        //Notification::send($user, new SendInviteNotification($event->getInvitation(), $event->getTeam(), $event->getMessage()));

       // Notification::route('mail', 'seong@chamberforge.com')->notify(new \App\Listeners\SendInviteNotification($event->getInvitation(), $event->getTeam(), $event->getMessage()));

        Mail::to($event->getInvitation()->email)->send(new UserInvitation($event->getInvitation(), $event->getTeam()));

//        return (new MailMessage)
//            ->subject($this->msg)
//            ->markdown('emails.users.invited',['team'=>$this->team, 'invitation'=>$this->invitation ]);
    }
}
