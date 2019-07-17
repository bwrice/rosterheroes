<?php

namespace App\StorableEvents;

use Spatie\EventProjector\ShouldBeStored;

final class HeroMeasurableCreated implements ShouldBeStored
{
    /**
     * @var int
     */
    public $measurableTypeID;
    /**
     * @var int
     */
    public $amountRaised;

    public function __construct(int $measurableTypeID, int $amountRaised = 0)
    {
        $this->measurableTypeID = $measurableTypeID;
        $this->amountRaised = $amountRaised;
    }
}
