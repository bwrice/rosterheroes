<?php

namespace App\Projectors;

use App\StorableEvents\SquadEssenceIncreased;
use App\Domain\Models\Squad;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

class SquadEssenceProjector implements Projector
{
    use ProjectsEvents;

    public function onSquadEssenceIncreased(SquadEssenceIncreased $event, string $aggregateUuid)
    {
        $squad = Squad::findUuid($aggregateUuid);
        $squad->spirit_essence += $event->amount;
        $squad->save();
    }
}
