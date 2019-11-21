<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\ShouldBeStored;

final class SkirmishAddedToCampaignStop implements ShouldBeStored
{
    /**
     * @var int
     */
    public $skirmishID;

    public function __construct(int $skirmishID)
    {
        $this->skirmishID = $skirmishID;
    }
}
