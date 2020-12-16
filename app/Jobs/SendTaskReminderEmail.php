<?php

namespace App\Jobs;

use App\Mail\TaskReminder;
use App\Notifications\AddedToProjectNotification;
use App\Notifications\SendTasksDueNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Carbon\Carbon;
use Log;
use Mail;
use Notification;

class SendTaskReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = User::all();
        $todayStart = Carbon::now()->startOfDay();
        $todayEnd = Carbon::now()->endOfDay();

        foreach($users as $user)
        {
            $tasks = $user->tasks()->whereBetween('due_on', [$todayStart, $todayEnd])->get();

            if (count($tasks) > 0 && $user->setting('daily_reminder_email'))
            {
                Notification::send($user, new SendTasksDueNotification($tasks));
            }

        }
    }
}
