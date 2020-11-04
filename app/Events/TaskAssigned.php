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

class TaskAssigned
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $task;
    private $owner;
    private $assigned;
    private $msg;

    public function getTask()
    {
        return $this->task;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function getAssigned()
    {
        return $this->assigned;
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
    public function __construct($owner, $assigned, $task, $msg='New task assigned.')
    {
        $this->task = $task;
        $this->owner = $owner;
        $this->assigned = $assigned;
        $this->msg = $msg;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //return new PrivateChannel('channel-name');
    }
}
