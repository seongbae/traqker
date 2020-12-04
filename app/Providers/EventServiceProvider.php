<?php

namespace App\Providers;

use App\Events\AddedToProject;
use App\Events\InviteCreated;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Laravelista\Comments\Events\CommentCreated;
use App\Listeners\SendNewCommentNotification;
use App\Listeners\NewRegistrationNotification;
use App\Listeners\SendTaskCompleteNotification;
use App\Listeners\SendInviteAcceptedNotification;
use App\Listeners\SendTaskAssignedNotification;
use App\Listeners\SendPaymentReceivedNotification;
use App\Listeners\SendAddedToProjectNotification;
use App\Listeners\SendInviteNotification;
use App\Listeners\HandleInviteCreated;
use App\Listeners\SendInviteDeclinedNotification;
use App\Events\TaskComplete;
use App\Events\InviteAccepted;
use App\Events\InviteDeclined;
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
        InviteCreated::class => [
            HandleInviteCreated::class
        ],
        InviteAccepted::class => [
            SendInviteAcceptedNotification::class
        ],
        InviteDeclined::class => [
            SendInviteDeclinedNotification::class
        ],
        AddedToProject::class => [
            SendAddedToProjectNotification::class
        ],
        TaskAssigned::class => [
            SendTaskAssignedNotification::class
        ],
        TaskComplete::class => [
            SendTaskCompleteNotification::class
        ],
        CommentCreated::class => [
            SendNewCommentNotification::class,
        ],
        PaymentSent::class => [
            SendPaymentReceivedNotification::class
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
