<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewComment extends Mailable
{
    use Queueable, SerializesModels;

    public $comment;
    public $task;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($comment)
    {
        $this->comment = $comment;
        $this->task = $comment->commentable;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.comments.newcomment')
                    ->subject('New comment: '.$this->comment->comment);
    }
}
