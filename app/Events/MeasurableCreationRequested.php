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

class MeasurableCreationRequested implements ShouldBeStored
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var string
     */
    public $measurableUuid;

    /**
     * @var array
     */
    public $attributes;

    /**
     * Create a new event instance.
     *
     * @param array $attributes
     *
     * @return void
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
        $this->measurableUuid = $attributes['uuid'];
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
