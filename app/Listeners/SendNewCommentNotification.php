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
use Log;

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

        foreach($event->comment->commentable->users as $assignee)
        {
            if (!in_array($assignee, $notifyUsers) && $assignee->id != $event->comment->commenter_id)
                $notifyUsers[] = $assignee;
        }

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
