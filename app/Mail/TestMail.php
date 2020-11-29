<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sender;
    public $subject;
    public $body;

    public function __construct($sender, $subject, $body) {
        $this->sender = $sender;
        $this->subject = $subject;
        $this->body = $body;
    }

    public function build() {
        return $this
            ->from($this->sender)
            ->subject($this->subject)
            ->markdown('emails.tests.testmail');
    }
}
