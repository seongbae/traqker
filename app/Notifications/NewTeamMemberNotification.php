<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use App\Mail\TeamMemberAdded;

class NewTeamMemberNotification extends Notification
{
    use Queueable;

    private $causer;
    private $team;
    private $msg;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($causer, $team, $msg=null)
    {
        $this->causer = $causer;
        $this->team = $team;
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
        $url = route('teams.show', $this->team);

        return (new MailMessage)
                    ->subject($this->msg)
                    ->markdown('emails.members.teamjoined',['url'=>$url,'team'=>$this->team]);

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
            'name' => $this->team->name,
            'description' => $this->team->name,
            'notif_msg'=>$this->msg,
            'link'=>route('teams.show', $this->team),
            'image'=>$this->causer->photo
        ];
    }
}
