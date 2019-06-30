<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/16/19
 * Time: 10:36 PM
 */

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Spatie\EventProjector\ShouldBeStored;

class PlayerSpiritCreationRequested implements ShouldBeStored
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var string
     */
    public $playerSpiritUuid;

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
        $this->playerSpiritUuid = $attributes['uuid'];
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