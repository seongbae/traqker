<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class TaskAssignedNotification extends Notification
{
    use Queueable;

    private $user;
    private $task;
    private $msg;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $task, $msg=null)
    {
        $this->user = $user;
        $this->task = $task;
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
        $url = route('tasks.show', $this->task);

        return (new MailMessage)
                    ->subject($this->msg)
                    ->markdown('emails.tasks.assigned',['task'=>$this->task]);
                    
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
            'image'=>'/storage/'.$this->user->photo
        ];
    }
}
