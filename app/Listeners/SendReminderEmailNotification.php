<?php

namespace App\Listeners;

use App\Notifications\SendTasksDueNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\TaskAssignedNotification;
use Notification;
use App\Events\TaskAssigned;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class SendReminderEmailNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle()
    {
        $user = User::first();

        $todayStart = Carbon::now()->startOfDay();
        $todayEnd = Carbon::now()->endOfDay();

        $tasks = $user->tasks()->whereBetween('due_on', [$todayStart, $todayEnd])->get();

        Notification::send($user, new SendTasksDueNotification($tasks));
    }
}
