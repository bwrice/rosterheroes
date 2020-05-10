<?php

namespace App\Projectors;

use App\Domain\Models\Squad;
use App\StorableEvents\SquadDealsDamageToMinion;
use App\StorableEvents\SquadKillsMinion;
use Spatie\EventSourcing\Projectors\ProjectsEvents;
use Spatie\EventSourcing\Projectors\QueuedProjector;

final class SquadCombatStatsProjector implements QueuedProjector
{
    use ProjectsEvents;

    public function onSquadDealsDamageToMinion(SquadDealsDamageToMinion $event, string $aggregateUuid)
    {
        $squad = Squad::findUuidOrFail($aggregateUuid);
        $squad->damage_dealt += $event->damage;
        $squad->save();
    }

    public function onSquadKillsMinion(SquadKillsMinion $event, string $aggregateUuid)
    {
        $squad = Squad::findUuidOrFail($aggregateUuid);
        $squad->minion_kills++;
        $squad->save();
    }
}
