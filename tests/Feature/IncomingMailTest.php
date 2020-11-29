<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\ReceivedMail;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class IncomingMailTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        config(['mail.driver' => 'log']);

        User::factory()->make();
    }

    function test_incoming_mail_is_saved_to_the_mails_table() {
        // Given: we have an e-mail﻿
        $email = new TestMail(
            $sender = 'sender@example.com',
            $subject = 'Test E-mail',
            $body = 'Some example text in the body'
        );

        // When: we receive that e-mail
        Mail::to('test@traqker.test')->send($email);

        // Then: we assert the e-mails (meta)data was stored
        $this->assertCount(1, ReceivedMail::all());

//        tap(ReceivedMail::first(), function ($mail) use ($sender, $subject, $body) {
//            $this->assertEquals($sender, $mail->sender);
//            $this->assertEquals($subject, $mail->subject);
//            $this->assertContains($body, $mail->body);
//        });
    }
}
