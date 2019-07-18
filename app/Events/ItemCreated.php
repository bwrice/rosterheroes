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

final class ItemCreationRequested implements ShouldBeStored
{
    /**
     * @var array
     */
    public $attributes;

    /**
     * ItemCreationRequested constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
        $this->itemUuid = $attributes['uuid'];
    }
}
