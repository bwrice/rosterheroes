<?php

namespace App\Projectors;

use App\StorableEvents\SquadExperienceIncreased;
use App\Domain\Models\Squad;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

class SquadExperienceProjector implements Projector
{
    use ProjectsEvents;

    public function onSquadExperienceIncreased(SquadExperienceIncreased $event, string $aggregateUuid)
    {
        $squad = Squad::findUuid($aggregateUuid);
        $squad->experience += $event->amount;
        $squad->save();
    }
}
