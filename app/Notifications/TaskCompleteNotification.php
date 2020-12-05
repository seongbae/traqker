<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class TaskCompleteNotification extends Notification
{
    use Queueable;

    private $user;
    private $task;
    private $subject;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $task)
    {
        $this->user = $user;
        $this->task = $task;
        $this->subject = "Task complete: ".$task->name;
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
             ->subject($this->subject)
             ->markdown('emails.tasks.complete',['task'=>$this->task]);
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
            'notif_msg'=>$this->subject,
            'link'=>route('tasks.show', $this->task),
            'image'=>'/storage/'.$this->user->photo
        ];
    }
}