<?php

namespace App\Projectors;

use App\Events\SquadGoldIncreased;
use App\Domain\Models\Squad;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class SquadGoldProjector implements Projector
{
    use ProjectsEvents;

    /*
     * Here you can specify which event should trigger which method.
     */
    protected $handlesEvents = [
        SquadGoldIncreased::class => 'onSquadGoldIncreased'
    ];

    public function onSquadGoldIncreased(SquadGoldIncreased $event)
    {
        $squad = Squad::uuid($event->squadUuid);
        $squad->gold += $event->amount;
        $squad->save();
    }

    public function streamEventsBy()
    {
        return 'squadUuid';
    }
}