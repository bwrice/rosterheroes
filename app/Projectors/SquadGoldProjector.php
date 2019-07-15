<?php

namespace App\Projectors;

use App\Events\SquadGoldIncreased;
use App\Domain\Models\Squad;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class SquadGoldProjector implements Projector
{
    use ProjectsEvents;

    public function onSquadGoldIncreased(SquadGoldIncreased $event, string $uuid)
    {
        $squad = Squad::uuid($uuid);
        $squad->gold += $event->amount;
        $squad->save();
    }
}