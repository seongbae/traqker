<?php

namespace App\Observers;

use App\Models\Hour;
use App\Events\TaskUpdated;
use Auth;

class HourObserver
{
    /**
     * Handle the hour "created" event.
     *
     * @param  \App\Hour  $hour
     * @return void
     */
    public function created(Hour $hour)
    {
        if ($hour->task)
            event(new TaskUpdated(Auth::user(), $hour->task, "New hours has been added"));
    }

    /**
     * Handle the hour "updated" event.
     *
     * @param  \App\Hour  $hour
     * @return void
     */
    public function updated(Hour $hour)
    {
        //
    }

    /**
     * Handle the hour "deleted" event.
     *
     * @param  \App\Hour  $hour
     * @return void
     */
    public function deleted(Hour $hour)
    {
        //
    }

    /**
     * Handle the hour "restored" event.
     *
     * @param  \App\Hour  $hour
     * @return void
     */
    public function restored(Hour $hour)
    {
        //
    }

    /**
     * Handle the hour "force deleted" event.
     *
     * @param  \App\Hour  $hour
     * @return void
     */
    public function forceDeleted(Hour $hour)
    {
        //
    }
}
