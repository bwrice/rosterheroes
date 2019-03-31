<?php

namespace App\Projectors;

use App\Events\SquadExperienceIncreased;
use App\Domain\Models\Squad;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class SquadExperienceProjector implements Projector
{
    use ProjectsEvents;

    /*
     * Here you can specify which event should trigger which method.
     */
    protected $handlesEvents = [
        SquadExperienceIncreased::class => 'onSquadExperienceIncreased'
    ];

    public function onSquadExperienceIncreased(SquadExperienceIncreased $event)
    {
        $squad = Squad::uuid($event->squadUuid);
        $squad->experience += $event->amount;
        $squad->save();
    }


    public function streamEventsBy()
    {
        return 'squadUuid';
    }
}