<?php

namespace App\StorableEvents;

use App\Domain\Models\SlotType;
use Spatie\EventProjector\ShouldBeStored;

final class SquadSlotsAdded implements ShouldBeStored
{
    /**
     * @var SlotType
     */
    public $slotType;
    /**
     * @var int
     */
    public $count;

    public function __construct(SlotType $slotType, int $count)
    {
        $this->slotType = $slotType;
        $this->count = $count;
    }
}
