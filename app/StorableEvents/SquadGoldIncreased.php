<?php

namespace App\StorableEvents;

use Spatie\EventProjector\ShouldBeStored;

final class SquadGoldIncreased implements ShouldBeStored
{
    /**
     * @var int
     */
    public $amount;

    public function __construct(int $amount)
    {
        $this->amount = $amount;
    }
}
