<?php

namespace App\Projectors;

use App\Events\SquadFavorIncreased;
use App\Domain\Models\Squad;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class SquadFavorProjector implements Projector
{
    use ProjectsEvents;

    /*
     * Here you can specify which event should trigger which method.
     */
    protected $handlesEvents = [
        SquadFavorIncreased::class => 'onSquadFavorIncreased'
    ];

    public function onSquadFavorIncreased(SquadFavorIncreased $event)
    {
        $squad = Squad::uuid($event->squadUuid);
        $squad->favor += $event->amount;
        $squad->save();
    }

    public function streamEventsBy()
    {
        return 'squadUuid';
    }
}