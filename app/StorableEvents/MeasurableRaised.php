<?php

namespace App\StorableEvents;

use Spatie\EventSourcing\ShouldBeStored;

final class MeasurableRaised implements ShouldBeStored
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
