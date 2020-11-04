<?php

namespace App\Observers;

use App\Models\Attachment;
use App\Events\TaskUpdated;
use App\Models\Task;
use Auth;
use Illuminate\Support\Facades\Log;

class AttachmentObserver
{
    /**
     * Handle the attachment "created" event.
     *
     * @param  \App\Attachment  $attachment
     * @return void
     */
    public function created(Attachment $attachment)
    {
        // Log::info('Attachment created');

        // Log::info('Attachment attachable: '.json_encode($attachment->attachable));

        if ($attachment->attachable instanceof Task)
        {
            event(new TaskUpdated(Auth::user(), $attachment->attachable, "New attachment added"));
        }
    }

    /**
     * Handle the attachment "updated" event.
     *
     * @param  \App\Attachment  $attachment
     * @return void
     */
    public function updated(Attachment $attachment)
    {
        //
    }

    /**
     * Handle the attachment "deleted" event.
     *
     * @param  \App\Attachment  $attachment
     * @return void
     */
    public function deleted(Attachment $attachment)
    {
        //
    }

    /**
     * Handle the attachment "restored" event.
     *
     * @param  \App\Attachment  $attachment
     * @return void
     */
    public function restored(Attachment $attachment)
    {
        //
    }

    /**
     * Handle the attachment "force deleted" event.
     *
     * @param  \App\Attachment  $attachment
     * @return void
     */
    public function forceDeleted(Attachment $attachment)
    {
        //
    }
}
