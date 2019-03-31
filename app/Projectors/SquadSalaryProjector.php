<?php

namespace App\Projectors;

use App\Events\SquadSalaryIncreased;
use App\Domain\Models\Squad;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class SquadSalaryProjector implements Projector
{
    use ProjectsEvents;

    /*
     * Here you can specify which event should trigger which method.
     */
    protected $handlesEvents = [
        SquadSalaryIncreased::class => 'onSquadSalaryIncreased'
    ];

    public function onSquadSalaryIncreased(SquadSalaryIncreased $event)
    {
        $squad = Squad::uuid($event->squadUuid);
        $squad->salary += $event->amount;
        $squad->save();
    }

    public function streamEventsBy()
    {
        return 'squadUuid';
    }
}