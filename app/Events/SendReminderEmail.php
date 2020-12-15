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

class AddedToProject
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $project;
    private $users;

    public function getProject()
    {
        return $this->project;
    }

    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($users, $project)
    {
        $this->project = $project;
        $this->users = $users;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //return new PrivateChannel('App.User.'.$this->user->id);
    }
}
