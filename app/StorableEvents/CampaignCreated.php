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

final class CampaignCreated implements ShouldBeStored
{
    /**
     * @var int
     */
    public $squadID;
    /**
     * @var int
     */
    public $weekID;
    /**
     * @var int
     */
    public $continentID;

    public function __construct(int $squadID, int $weekID, int $continentID)
    {
        $this->squadID = $squadID;
        $this->weekID = $weekID;
        $this->continentID = $continentID;
    }
}
