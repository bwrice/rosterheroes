<?php

namespace App\Projectors;

use App\Domain\Models\Squad;
use App\StorableEvents\SquadExperienceIncreased;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

class SquadProjector implements Projector
{
    use ProjectsEvents;

    public function onSquadExperienceIncreased(SquadExperienceIncreased $event, string $aggregateUuid)
    {
        $squad = Squad::findUuid($aggregateUuid);
        $squad->experience += $event->amount;
        $squad->save();
    }

}
