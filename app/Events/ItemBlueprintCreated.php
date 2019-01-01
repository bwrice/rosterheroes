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

class ItemBlueprintCreated implements ShouldBeStored
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $itemBlueprintAttributes;
    public $item_blueprint_id;

    /**
     * Create a new event instance.
     *
     * @param $itemBlueprintAttributes
     *
     * @return void
     */
    public function __construct(array $itemBlueprintAttributes)
    {
        $this->itemBlueprintAttributes = $itemBlueprintAttributes;
        $this->item_blueprint_id = $itemBlueprintAttributes['id'];
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
