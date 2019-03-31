<?php

namespace App\Projectors;

use App\Events\MeasurableCreationRequested;
use App\Domain\Models\Measurable;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class MeasurableProjector implements Projector
{
    use ProjectsEvents;

    /*
     * Here you can specify which event should trigger which method.
     */
    protected $handlesEvents = [
        MeasurableCreationRequested::class => 'onMeasurableCreationRequested'
    ];

    public function onMeasurableCreationRequested(MeasurableCreationRequested $event)
    {
        Measurable::create($event->attributes);
    }

    public function streamEventsBy()
    {
        return 'measurableUuid';
    }

}