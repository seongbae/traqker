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

class TeamMemberAdded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $causer;
    private $team;
    private $user;
    private $msg;

    public function getTeam()
    {
        return $this->team;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getCauser()
    {
        return $this->causer;
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
    public function __construct($causer, $team, $user, $msg='You have been added to a team.')
    {
        $this->causer = $causer;
        $this->team = $team;
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
        //return new PrivateChannel('App.User.'.$this->user->id);
    }
}
