<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class PaymentReceivedNotification extends Notification
{
    use Queueable;

    private $payer;
    private $payment;
    private $msg;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($payer, $payment, $msg=null)
    {
        $this->payer = $payer;
        $this->payment = $payment;
        $this->msg = $msg;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url('/my-account#transactions/');

        return (new MailMessage)
                    ->subject($this->msg)
                    ->markdown('emails.payments.received',['url'=>$url,'payment'=>$this->payment]);

    }
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'name' => 'Payment Received',
            'description' => $this->msg,
            'notif_msg'=>$this->msg,
            'link'=>url('/my-account#transactions/'),
            'image'=>$this->payer->photo
        ];
    }
}
