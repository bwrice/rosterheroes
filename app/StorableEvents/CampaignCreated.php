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
     * @var int
     */
    public $weekID;
    /**
     * @var int
     */
    public $continentID;
    /**
     * @var string
     */
    public $campaignUuid;

    public function __construct(int $weekID, int $continentID, string $campaignUuid)
    {
        $this->weekID = $weekID;
        $this->continentID = $continentID;
        $this->campaignUuid = $campaignUuid;
    }
}
