<?php

namespace App\Projectors;

use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

final class ItemCombatStatsProjector implements Projector
{
    use ProjectsEvents;

    public function onEventHappened(EventHappened $event)
    {
    }
}
