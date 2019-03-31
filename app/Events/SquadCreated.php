<?php

namespace App\Events;

use App\Domain\Models\Squad;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SquadCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $squad;
    /**
     * Create a new event instance.
     * @param \App\Domain\Models\Squad $squad
     *
     * @return void
     */
    public function __construct(Squad $squad)
    {
        $this->squad = $squad;
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
