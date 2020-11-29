<?php

namespace App\Console\Commands;

use App\Mail\TestMail;
use App\Models\ReceivedMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ReceiveEmailCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'receive:email {slug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        config(['mail.driver' => 'log']);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = new TestMail(
            $sender = 'seong@lnidigital.com',
            $subject = 'Test E-mail 2',
            $body = 'Some example text in the body'
        );

        $toEmail = $this->argument('slug') . '@traqker.test';

        // When: we receive that e-mail
        Mail::to($toEmail)->send($email);

        echo "mail sent";


    }
}
