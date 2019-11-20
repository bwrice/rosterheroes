<?php

namespace App\StorableEvents;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Spatie\EventSourcing\ShouldBeStored;

class SquadFavorIncreased implements ShouldBeStored
{
    /**
     * @var int
     */
    public $amount;

    /**
     * SquadFavorIncreased constructor.
     * @param int $amount
     */
    public function __construct(int $amount)
    {
        $this->amount = $amount;
    }
}
