<?php

namespace App\Jobs;

use App\Notifications\SendTasksDueNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Carbon\Carbon;
use Log;

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

        Log::info('SendTaskReminderEmail started...');

        foreach($users as $user)
        {
            Log::info('Looking at '.$user->name);

            $tasks = $user->tasks()->whereBetween('due_on', [$todayStart, $todayEnd])->get();

            Log::info($user->name . ' has '.count($tasks).' tasks due today.');

            if (count($tasks) > 0 && $user->setting('daily_reminder_email'))
            {
                $user->notify(new SendTasksDueNotification($tasks));
                Log::info('Notified '.$user->name);
            }

        }

        Log::info('SendTaskReminderEmail ended...');
    }
}
