<?php

namespace App\StorableEvents;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Spatie\EventProjector\ShouldBeStored;

final class CampaignCreated implements ShouldBeStored
{
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }
}
