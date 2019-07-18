<?php

namespace App\StorableEvents;

use Spatie\EventProjector\ShouldBeStored;

final class SquadEssenceIncreased implements ShouldBeStored
{
    /**
     * @var int
     */
    public $amount;

    /**
     * SquadEssenceIncreased constructor.
     * @param int $amount
     */
    public function __construct(int $amount)
    {
        $this->amount = $amount;
    }
}
