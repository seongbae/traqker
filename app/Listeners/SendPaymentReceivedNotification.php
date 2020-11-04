<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\PaymentReceivedNotification;
use Notification;
use App\Events\PaymentSent;
use Illuminate\Support\Facades\Log;

class SendPaymentReceivedNotification
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
    public function handle(PaymentSent $event)
    {
        Notification::send($event->getPayee(), new PaymentReceivedNotification($event->getPayer(), $event->getPayment(), $event->getMessage()));
    }
}
