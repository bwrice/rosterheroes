<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Spatie\EventProjector\ShouldBeStored;

class SquadGoldIncreased implements ShouldBeStored
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * @var string
     */
    public $squadUuid;
    /**
     * @var int
     */
    public $amount;

    public function __construct(string $squadUuid, int $amount)
    {
        $this->squadUuid = $squadUuid;
        $this->amount = $amount;
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
