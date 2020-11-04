<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserInvitation extends Mailable
{
    use Queueable, SerializesModels;

    private $invitation;
    private $team;
    private $msg;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($invitation, $team, $msg="You have been invited")
    {
        $this->invitation = $invitation;
        $this->team = $team;
        $this->msg = $msg;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->msg)
                    ->markdown('emails.users.invited',['team'=>$this->team, 'invitation'=>$this->invitation ]);
    }
}
