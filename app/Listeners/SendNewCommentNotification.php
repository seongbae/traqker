<?php

namespace App\Listeners;

use Laravelista\Comments\Events\CommentCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use Laravelista\Comments\Comment;
use App\Models\Task;
use Auth;
use App\Notifications\NewCommentNotification;
use Notification;

class SendNewCommentNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CommentCreated  $event
     * @return void
     */
    public function handle(CommentCreated $event)
    {

        $notifyUsers = [];

        if ($event->comment->commenter_id != $event->comment->commentable->user_id)
            $notifyUsers[] = User::find($event->comment->commentable->user_id);

        if ($event->comment->commentable->assigned_to && $event->comment->commenter_id != $event->comment->commentable->assigned_to)
            $notifyUsers[] = User::find($event->comment->commentable->assigned_to);

        if ($event->comment->child_id != null)
        {
            $parentComment = Comment::find($event->comment->child_id);
            $parentCommenter = $parentComment->commenter;

            if ($parentComment->commenter_id != $event->comment->commenter_id && !in_array($parentCommenter, $notifyUsers))
                $notifyUsers[] = $parentCommenter;
        }

        $msg = "\"".$event->comment->comment."\""." on <i>".$event->comment->commentable->name."</i>";

        if (count($notifyUsers)>0)
           Notification::send($notifyUsers, new NewCommentNotification($event->comment->commenter, $event->comment, $msg));

    }
}
