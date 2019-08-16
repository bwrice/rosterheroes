<?php

namespace App\Projectors;

use App\StorableEvents\SquadExperienceIncreased;
use App\Domain\Models\Squad;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class SquadExperienceProjector implements Projector
{
    use ProjectsEvents;

    public function onSquadExperienceIncreased(SquadExperienceIncreased $event, string $uuid)
    {
        $squad = Squad::findUuid($uuid);
        $squad->experience += $event->amount;
        $squad->save();
    }
}
