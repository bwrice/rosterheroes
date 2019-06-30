<?php

namespace App\Events;

use App\Domain\Models\HeroRace;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Spatie\EventProjector\ShouldBeStored;

class SquadHeroPostAdded implements ShouldBeStored
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var string
     */
    public $squadUuid;
    /**
     * @var int
     */
    public $heroRaceID;

    /**
     * SquadHeroPostAdded constructor.
     * @param string $squadUuid
     * @param int $heroRaceID
     */
    public function __construct(string $squadUuid, int $heroRaceID)
    {
        $this->squadUuid = $squadUuid;
        $this->heroRaceID = $heroRaceID;
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
