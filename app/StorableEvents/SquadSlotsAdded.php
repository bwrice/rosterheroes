<?php

namespace App\StorableEvents;

use App\Domain\Models\SlotType;
use Spatie\EventProjector\ShouldBeStored;

final class SquadSlotsAdded implements ShouldBeStored
{
    /**
     * @var string
     */
    public $slotTypeName;
    /**
     * @var int
     */
    public $count;

    public function __construct(string $slotTypeName, int $count)
    {
        $this->slotTypeName = $slotTypeName;
        $this->count = $count;
    }
}
