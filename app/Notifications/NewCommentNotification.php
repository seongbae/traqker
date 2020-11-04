<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use App\Mail\TeamMemberAdded;
use Helper;

class NewCommentNotification extends Notification
{
    use Queueable;

    private $user;
    private $comment;
    private $msg;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $comment, $msg=null)
    {
        $this->user = $user;
        $this->comment = $comment;
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
        $url = "";

        if ($this->comment->commentable instanceof Task) {
            //$msg = "<strong>".$event->comment->commenter->name . "</strong>: \"".$event->comment->comment."\"";
            $url = route('tasks.show', $this->comment->commentable);
        }

        return (new MailMessage)
                    ->subject("New comment by ".$this->comment->commenter->name.": ".Helper::limitText($this->comment->comment, 40))
                    ->markdown('emails.comments.newcomment',['url'=>$url,'comment'=>$this->comment,'task'=>$this->comment->commentable]);
                    
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
            'name' => $this->comment->comment,
            'description' => $this->comment->comment,
            'notif_msg'=>$this->msg,
            'link'=>route('tasks.show', $this->comment->commentable),
            'image'=>'/storage/'.$this->user->photo
        ];
    }
}
