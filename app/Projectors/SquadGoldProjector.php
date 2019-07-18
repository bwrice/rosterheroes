<?php

namespace App\Projectors;

use App\StorableEvents\SquadGoldIncreased;
use App\Domain\Models\Squad;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class SquadGoldProjector implements Projector
{
    use ProjectsEvents;

    public function onSquadGoldIncreased(SquadGoldIncreased $event, string $aggregateUuid)
    {
        $squad = Squad::uuid($aggregateUuid);
        $squad->gold += $event->amount;
        $squad->save();
    }
}