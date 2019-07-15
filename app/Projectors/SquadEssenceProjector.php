<?php

namespace App\Projectors;

use App\Events\SquadEssenceIncreased;
use App\Domain\Models\Squad;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class SquadEssenceProjector implements Projector
{
    use ProjectsEvents;

    public function onSquadEssenceIncreased(SquadEssenceIncreased $event, string $aggregateUuid)
    {
        $squad = Squad::uuid($aggregateUuid);
        $squad->spirit_essence += $event->amount;
        $squad->save();
    }
}