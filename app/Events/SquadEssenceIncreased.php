<?php

namespace App\Events;

use Spatie\EventProjector\ShouldBeStored;

class SquadEssenceIncreased implements ShouldBeStored
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
