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
     * @var string
     */
    public $hasMeasurablesType;
    /**
     * @var int
     */
    public $hasMeasurablesID;
    /**
     * @var int
     */
    public $amountRaised;

    public function __construct(int $measurableTypeID, string $hasMeasurablesType, int $hasMeasurablesID, int $amountRaised = 0)
    {
        $this->measurableTypeID = $measurableTypeID;
        $this->hasMeasurablesType = $hasMeasurablesType;
        $this->hasMeasurablesID = $hasMeasurablesID;
        $this->amountRaised = $amountRaised;
    }
}
