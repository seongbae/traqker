<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use App\Mail\TeamMemberAdded;

class InviteDeclinedNotification extends Notification
{
    use Queueable;

    private $invite;
    private $subject;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($invite)
    {
        $this->invite = $invite;
        $this->subject = $invite->toUser->name . ' has declined your invite.';
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
        return (new MailMessage)
                    ->subject($this->subject)
                    ->markdown('emails.invites.declined',['invite'=>$this->invite]);

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
            'name' => $this->invite->toUser->name,
            'description' => $this->subject,
            'notif_msg'=>$this->subject,
            'link'=>url('/dashboard'),
            'image'=>$this->invite->toUser->photo
        ];
    }
}
