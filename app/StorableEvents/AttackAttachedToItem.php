<?php

namespace App\StorableEvents;

use Spatie\EventProjector\ShouldBeStored;

final class AttackAttachedToItem implements ShouldBeStored
{
    /**
     * @var int
     */
    public $attackID;

    public function __construct(int $attackID)
    {
        $this->attackID = $attackID;
    }
}
