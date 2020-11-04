<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Laravelista\Comments\Events\CommentCreated;
use App\Listeners\SendNewCommentNotification;
use App\Listeners\NewRegistrationNotification;
use App\Listeners\SendTaskUpdatedNotification;
use App\Listeners\SendNewTeamMemberNotification;
use App\Listeners\SendTaskAssignedNotification;
use App\Listeners\SendPaymentReceivedNotification;
use App\Listeners\SendInviteNotification;
use App\Events\TaskUpdated;
use App\Events\TeamMemberAdded;
use App\Events\TaskAssigned;
use App\Events\PaymentSent;
use App\Events\UserInvited;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            NewRegistrationNotification::class,
        ],
        CommentCreated::class => [
            SendNewCommentNotification::class,
        ],
        TaskAssigned::class => [
            SendTaskAssignedNotification::class
        ],
        TaskUpdated::class => [
            SendTaskUpdatedNotification::class
        ],
        TeamMemberAdded::class => [
            SendNewTeamMemberNotification::class
        ],
        PaymentSent::class => [
            SendPaymentReceivedNotification::class
        ],
        UserInvited::class => [
            SendInviteNotification::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
