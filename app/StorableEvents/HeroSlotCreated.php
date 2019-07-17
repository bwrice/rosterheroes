<?php

namespace App\StorableEvents;

use Spatie\EventProjector\ShouldBeStored;

final class HeroSlotCreated implements ShouldBeStored
{
    /**
     * @var int
     */
    public $slotTypeID;

    public function __construct(int $slotTypeID)
    {
        $this->slotTypeID = $slotTypeID;
    }
}
