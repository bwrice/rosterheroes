<?php

namespace App\Projectors;

use App\Events\MeasurableCreationRequested;
use App\Domain\Models\Measurable;
use App\StorableEvents\MeasurableCreated;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class MeasurableProjector implements Projector
{
    use ProjectsEvents;

    public function onMeasurableCreated(MeasurableCreated $event, string $aggregateUuid)
    {
        Measurable::query()->create([
            'uuid' => $aggregateUuid,
            'measurable_type_id' => $event->measurableTypeID,
            'has_measurables_type' => $event->hasMeasurablesType,
            'has_measurables_id' => $event->hasMeasurablesID,
            'amount_raised' => $event->amountRaised
        ]);
    }
}