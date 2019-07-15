<?php

namespace App\Projectors;

use App\Events\SquadExperienceIncreased;
use App\Domain\Models\Squad;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class SquadExperienceProjector implements Projector
{
    use ProjectsEvents;

    public function onSquadExperienceIncreased(SquadExperienceIncreased $event, string $uuid)
    {
        $squad = Squad::uuid($uuid);
        $squad->experience += $event->amount;
        $squad->save();
    }
}