<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\NewUserRegistered;
use Illuminate\Support\Facades\Mail;

class NewRegistrationNotification
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
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $to = [
            [
                'email' => option('notification_email'),
                'name' => config('app.name'),
            ]
        ];

        if (option('notification_email'))
            Mail::to(option('notification_email'))->send(new NewUserRegistered($event->user));
    }
}
