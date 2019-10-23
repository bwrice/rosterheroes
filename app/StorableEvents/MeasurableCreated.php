<?php

namespace App\StorableEvents;

use Spatie\EventProjector\ShouldBeStored;

final class MeasurableCreated implements ShouldBeStored
{
    /**
     * @var int
     */
    public $measurableTypeID;
    /**
     * @var int
     */
    public $heroID;
    /**
     * @var int
     */
    public $amountRaised;

    public function __construct(int $measurableTypeID, int $heroID, int $amountRaised = 0)
    {
        $this->measurableTypeID = $measurableTypeID;
        $this->heroID = $heroID;
        $this->amountRaised = $amountRaised;
    }
}
