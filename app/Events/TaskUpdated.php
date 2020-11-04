<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TaskUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $task;
    private $user;
    private $msg;

    public function getTask()
    {
        return $this->task;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getMessage()
    {
        return $this->msg;
    }

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $task, $msg='Task has been updated.')
    {
        $this->task = $task;
        $this->user = $user;
        $this->msg = $msg;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
