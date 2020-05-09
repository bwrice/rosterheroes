<?php

namespace App\Projectors;

use App\Domain\Models\Hero;
use App\StorableEvents\HeroDealsDamageToMinion;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;
use Spatie\EventSourcing\Projectors\QueuedProjector;

final class HeroCombatStatsProjector implements QueuedProjector
{
    use ProjectsEvents;

    public function onEventHappened(HeroDealsDamageToMinion $event, string $aggregateUuid)
    {
        $hero = Hero::findUuidOrFail($aggregateUuid);
        $hero->damage_dealt += $event->damage;
        $hero->save();
    }
}
