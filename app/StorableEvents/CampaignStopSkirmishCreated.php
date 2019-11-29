<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\ShouldBeStored;

final class CampaignStopSkirmishCreated implements ShouldBeStored
{
    /**
     * @var int
     */
    public $campaignStopID;
    /**
     * @var int
     */
    public $skirmishID;

    public function __construct(int $campaignStopID, int $skirmishID)
    {
        $this->campaignStopID = $campaignStopID;
        $this->skirmishID = $skirmishID;
    }
}
