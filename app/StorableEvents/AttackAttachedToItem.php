<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\ShouldBeStored;

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
