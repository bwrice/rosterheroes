<?php

namespace App\Projectors;

use App\Domain\Models\Hero;
use App\StorableEvents\HeroDealsDamageToMinion;
use App\StorableEvents\HeroKillsMinion;
use Spatie\EventSourcing\Projectors\ProjectsEvents;
use Spatie\EventSourcing\Projectors\QueuedProjector;

final class HeroCombatStatsProjector implements QueuedProjector
{
    use ProjectsEvents;

    public function onHeroDealsDamageToMinion(HeroDealsDamageToMinion $event, string $aggregateUuid)
    {
        $hero = Hero::findUuidOrFail($aggregateUuid);
        $hero->damage_dealt += $event->damage;
        $hero->save();
    }

    public function onHeroKillsMinion(HeroKillsMinion $event, string $aggregateUuid)
    {
        $hero = Hero::findUuidOrFail($aggregateUuid);
        $hero->minion_kills++;
        $hero->save();
    }
}
