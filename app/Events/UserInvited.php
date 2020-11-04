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

class UserInvited
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $invitation;
    private $team;
    private $msg;

    public function getInvitation()
    {
        return $this->invitation;
    }

    public function getTeam()
    {
        return $this->team;
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
    public function __construct($invitation, $team, $msg='Task has been updated.')
    {
        $this->invitation = $invitation;
        $this->team = $team;
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
