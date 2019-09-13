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

final class HeroCreated implements ShouldBeStored
{
    /**
     * @var string
     */
    public $name;
    /**
     * @var int
     */
    public $heroClassID;
    /**
     * @var int
     */
    public $heroRaceID;
    /**
     * @var int
     */
    public $heroRankID;
    /**
     * @var int
     */
    public $combatPositionID;

    public function __construct(string $name, int $heroClassID, int $heroRaceID, int $heroRankID, int $combatPositionID)
    {
        $this->name = $name;
        $this->heroClassID = $heroClassID;
        $this->heroRaceID = $heroRaceID;
        $this->heroRankID = $heroRankID;
        $this->combatPositionID = $combatPositionID;
    }
}
