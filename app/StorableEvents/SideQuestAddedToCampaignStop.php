<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\ShouldBeStored;

final class SideQuestAddedToCampaignStop implements ShouldBeStored
{
    /**
     * @var int
     */
    public $sideQuestID;

    public function __construct(int $sideQuestID)
    {
        $this->sideQuestID = $sideQuestID;
    }
}
