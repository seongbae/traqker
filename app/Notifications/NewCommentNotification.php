<?php

namespace App\Notifications;

use Edujugon\PushNotification\Channels\FcmChannel;
use Edujugon\PushNotification\Messages\PushMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use App\Mail\TeamMemberAdded;
use Helper;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

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
        $channels = ['database', FcmChannel::class];

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
        $url = "/";

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
            'image'=>$this->user->photo
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('New Comment')
            //->icon('/approved-icon.png')
            ->body($this->comment->commenter->name . ": ".$this->comment->comment)
            ->action('Go to Task', 'view_task')
            ->options(['TTL' => 1000])
            ->data(['url' => url(route('tasks.show', $this->comment->commentable))]);
        // ->badge()
        // ->dir()
        // ->image()
        // ->lang()
        // ->renotify()
        // ->requireInteraction()
        // ->tag()
        // ->vibrate()
    }

    public function toFcm($notifiable)
    {
        return (new PushMessage)
            ->title("New Comment")
            ->body($this->comment->commenter->name . ": ".$this->comment->comment)
            ->sound('default')
            //->icon()
            ->extra([
//                'title'=>'hello',
//                'body'=>'world',
                'entity' => 'task',
                'entity_id' => $this->comment->commentable->id,
                'android' => array('priority'=>'high')
            ]);
    }
}
