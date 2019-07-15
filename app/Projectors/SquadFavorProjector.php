<?php

namespace App\Projectors;

use App\Events\SquadFavorIncreased;
use App\Domain\Models\Squad;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class SquadFavorProjector implements Projector
{
    use ProjectsEvents;

    public function onSquadFavorIncreased(SquadFavorIncreased $event, string $aggregateUuid)
    {
        $squad = Squad::uuid($aggregateUuid);
        $squad->favor += $event->amount;
        $squad->save();
    }
}