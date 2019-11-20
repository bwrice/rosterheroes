<?php

namespace App\Projectors;

use App\StorableEvents\SquadGoldDecreased;
use App\StorableEvents\SquadGoldIncreased;
use App\Domain\Models\Squad;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

class SquadGoldProjector implements Projector
{
    use ProjectsEvents;

    public function onSquadGoldIncreased(SquadGoldIncreased $event, string $aggregateUuid)
    {
        $squad = Squad::findUuid($aggregateUuid);
        $squad->gold += $event->amount;
        $squad->save();
    }

    public function onSquadGoldDecreased(SquadGoldDecreased $event, string $aggregateUuid)
    {
        $squad = Squad::findUuid($aggregateUuid);
        $squad->gold -= $event->amount;
        $squad->save();
    }
}
