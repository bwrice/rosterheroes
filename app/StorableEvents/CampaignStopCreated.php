<?php

namespace App\StorableEvents;

use Spatie\EventProjector\ShouldBeStored;

final class CampaignStopCreated implements ShouldBeStored
{
    /**
     * @var int
     */
    public $campaignID;
    /**
     * @var int
     */
    public $questID;
    /**
     * @var int
     */
    public $provinceID;

    public function __construct(int $campaignID, int $questID, int $provinceID)
    {
        $this->campaignID = $campaignID;
        $this->questID = $questID;
        $this->provinceID = $provinceID;
    }
}
