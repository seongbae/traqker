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
use BeyondCode\Mailbox\Facades\Mailbox;
use App\MailHandler;
use App\Models\Team;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        CalendarResource::withoutWrapping();

        Mailbox::to('{projectname}@'.config('app.mail_domain'), MailHandler::class);

//        view()->composer('teams.menus', function ($view) {
//            $view->with('team', Team::first())->with('page', '_page');
//        });
    }
}
