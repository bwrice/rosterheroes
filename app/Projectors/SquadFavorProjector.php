<?php

namespace App\Projectors;

use App\Events\SquadFavorIncreased;
use App\Domain\Models\Squad;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class SquadFavorProjector implements Projector
{
    use ProjectsEvents;

    public function onSquadFavorIncreased(SquadFavorIncreased $event, string $uuid)
    {
        $squad = Squad::uuid($uuid);
        $squad->favor += $event->amount;
        $squad->save();
    }
}