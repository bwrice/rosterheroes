<?php

namespace App\Projectors;

use App\Events\SquadEssenceIncreased;
use App\Domain\Models\Squad;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class SquadEssenceProjector implements Projector
{
    use ProjectsEvents;

    /*
     * Here you can specify which event should trigger which method.
     */
    protected $handlesEvents = [
        SquadEssenceIncreased::class => 'onSquadEssenceIncreased'
    ];

    public function onSquadEssenceIncreased(SquadEssenceIncreased $event)
    {
        $squad = Squad::uuid($event->squadUuid);
        $squad->spirit_essence += $event->amount;
        $squad->save();
    }

    public function streamEventsBy()
    {
        return 'squadUuid';
    }
}