<?php

namespace App\Projectors;

use App\StorableEvents\SquadFavorIncreased;
use App\Domain\Models\Squad;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

class SquadFavorProjector implements Projector
{
    use ProjectsEvents;

    public function onSquadFavorIncreased(SquadFavorIncreased $event, string $aggregateUuid)
    {
        $squad = Squad::findUuid($aggregateUuid);
        $squad->favor += $event->amount;
        $squad->save();
    }
}
