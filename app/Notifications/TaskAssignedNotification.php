<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

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
        $channels = ['database'];

        if ($this->user->setting('browser_notification'))
            $channels[] = WebPushChannel::class;

        if ($this->user->setting('email_notification'))
            $channels[] = 'mail';

        return $channels;
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
            'image'=>'$this->user->photo
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('New Task')
            //->icon('/approved-icon.png')
            ->body('New task assigned: '.$this->task->name)
            ->action('Go to Task', 'view_task')
            ->options(['TTL' => 1000])
            ->data(['url' => url(route('tasks.show', $this->task))]);
        // ->badge()
        // ->dir()
        // ->image()
        // ->lang()
        // ->renotify()
        // ->requireInteraction()
        // ->tag()
        // ->vibrate()
    }
}
