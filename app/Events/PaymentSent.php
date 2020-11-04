<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PaymentSent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $payer;
    private $payee;
    private $payment;
    private $msg;

    public function getPayer()
    {
        return $this->payer;
    }

    public function getPayee()
    {
        return $this->payee;
    }

    public function getPayment()
    {
        return $this->payment;
    }

    public function getMessage()
    {
        return $this->msg;
    }

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($payer, $payee, $payment, $msg='You received a payment.')
    {
        $this->payer = $payer;
        $this->payee = $payee;
        $this->payment = $payment;
        $this->msg = $msg;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //return new PrivateChannel('channel-name');
    }
}
