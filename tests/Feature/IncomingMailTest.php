<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\ReceivedMail;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Log;

class IncomingMailTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $project;

    public function setUp(): void
    {
        parent::setUp();

        config(['mail.driver' => 'log']);

        $this->user = User::factory()->make();
        $this->project = Project::factory()->make();
        $this->project->members()->attach($this->user->id);
    }

    function test_incoming_mail_is_saved_to_the_mails_table() {
        // Given: we have an e-mail﻿
        $email = new TestMail(
            $sender = 'sender@example.com',
            $subject = 'Test E-mail',
            $body = 'Some example text in the body'
        );

        // When: we receive that e-mail
        Mail::to('test@'.config('app.mail_domain'))->send($email);

        // Then: we assert the e-mails (meta)data was stored
        $this->assertCount(1, ReceivedMail::all());

//        tap(ReceivedMail::first(), function ($mail) use ($sender, $subject, $body) {
//            $this->assertEquals($sender, $mail->sender);
//            $this->assertEquals($subject, $mail->subject);
//            $this->assertContains($body, $mail->body);
//        });
    }

//    function test_task_created_from_mail() {
//
//        $email = new TestMail(
//            $sender = $this->user->email,
//            $subject = 'New Task',
//            $body = 'Some example text in the body'
//        );
//
//        Mail::to($this->project->slug.'@'.config('app.mail_domain'))->send($email);
//
//        // Then: we assert the e-mails (meta)data was stored
//        Log::info('asserting assertDatabaseHas..');
//        $this->assertDatabaseHas('tasks', ['name'=>$email->subject]);
//
//    }
}
