<?php

namespace App\Notifications;

use Edujugon\PushNotification\Channels\FcmChannel;
use Edujugon\PushNotification\Messages\PushMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class MessageReceivedNotification extends Notification
{
    use Queueable;

    private $user;
    private $message;
    private $subject;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
        $this->subject = 'New Message';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', FcmChannel::class];
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
                    ->markdown('emails.projects.assigned',['project'=>$this->project]);

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
            'name' => $this->project->name,
            'description' => $this->project->description,
            'notif_msg'=>$this->subject,
            'link'=>route('projects.show', $this->project),
            'image'=>$this->user->photo
        ];
    }

    public function toFcm($notifiable)
    {
        return (new PushMessage)
            ->title("New Message")
            ->body($this->message->body)
            ->sound('default')
            ->extra([
                'entity' => 'message',
                'entity_id' => $this->message->thread->id,
                'android' => array('priority'=>'high')
            ]);
    }
}
