<?php


namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class EmailInviteNotification extends Notification
{
    use Queueable;

    private $invitation;
    private $team;
    private $subject;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($invitation, $team, $msg=null)
    {
        $this->invitation = $invitation;
        $this->team = $team;
        $this->subject = "You have been invited to team: " . $this->team->name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
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
            ->subject($this->msg)
            ->markdown('emails.invites.createaccount',['team'=>$this->team, 'invitation'=>$this->invitation ]);

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
            'name' => $this->task->name,
            'description' => $this->task->description,
            'notif_msg'=>$this->msg,
            'link'=>route('tasks.show', $this->task),
            'image'=>$this->user->photo
        ];
    }
}
