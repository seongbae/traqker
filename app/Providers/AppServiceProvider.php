<?php

namespace App\Providers;

use App\Http\Resources\CalendarResource;
use Illuminate\Support\ServiceProvider;
use App\Observers\TaskObserver;
use App\Models\Task;
use App\Observers\AttachmentObserver;
use App\Models\Attachment;
use App\Observers\HourObserver;
use App\Models\Hour;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
//        Task::observe(TaskObserver::class);
//        Attachment::observe(AttachmentObserver::class);
//        Hour::observe(HourObserver::class);

        CalendarResource::withoutWrapping();
    }
}
