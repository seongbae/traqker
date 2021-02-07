<?php

namespace App\Console\Commands;

use App\Mail\TestMail;
use App\Models\ReceivedMail;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class SendChatMessage extends Command
{
    protected $signature = 'chat:message {message}';

    protected $description = 'Send chat message.';

    public function handle()
    {
        // Fire off an event, just randomly grabbing the first user for now
        $user = User::find(2);

        $thread = Thread::find(3);

//        $message = Message::create([
//            'user_id' => $user->id,
//            'message' => $this->argument('message')
//        ]);

        event(new \App\Events\MessageReceived($user, $thread, $this->argument('message')));
    }
}
