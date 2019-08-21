<?php

namespace App\Aggregates;

use App\StorableEvents\MeasurableCreated;
use App\StorableEvents\MeasurableRaised;
use Spatie\EventProjector\AggregateRoot;

final class MeasurableAggregate extends AggregateRoot
{
    public function createMeasurable(int $measurableTypID, string $hasMeasurablesType, int $hasMeasurablesID, $amountIncreased = 0)
    {
        $this->recordThat(new MeasurableCreated($measurableTypID, $hasMeasurablesType, $hasMeasurablesID, $amountIncreased));

        return $this;
    }

    public function raiseMeasurable(int $amount)
    {
        $this->recordThat(new MeasurableRaised($amount));

        return $this;
    }
}

