<?php

namespace App\Projectors;

use App\Events\SquadCreationRequested;
use App\Squad;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class SquadProjector implements Projector
{
    use ProjectsEvents;

    /*
     * Here you can specify which event should trigger which method.
     */
    protected $handlesEvents = [
        SquadCreationRequested::class => 'onSquadCreationRequested'
    ];

    public function onSquadCreationRequested(SquadCreationRequested $event)
    {
        Squad::create($event->attributes);
    }

    public function streamEventsBy()
    {
        return 'squadUuid';
    }
}