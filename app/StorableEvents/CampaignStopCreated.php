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
    /**
     * @var string
     */
    public $campaignStopUuid;

    public function __construct(int $campaignID, int $questID, int $provinceID, string $campaignStopUuid)
    {
        $this->campaignID = $campaignID;
        $this->questID = $questID;
        $this->provinceID = $provinceID;
        $this->campaignStopUuid = $campaignStopUuid;
    }
}
