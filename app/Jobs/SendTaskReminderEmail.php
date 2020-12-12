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

//        Log::info($todayStart);
//        Log::info($todayEnd);

        foreach($users as $user)
        {
            $tasks = $user->tasks()->whereBetween('due_on', [$todayStart, $todayEnd])->get();

            if (count($tasks) > 0)
                $user->notify(new SendTasksDueNotification($tasks));
        }
    }
}
