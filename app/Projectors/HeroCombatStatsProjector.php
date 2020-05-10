<?php

namespace App\Projectors;

use App\Domain\Models\Hero;
use App\StorableEvents\HeroDealsDamageToSideQuestMinion;
use App\StorableEvents\HeroKillsSideQuestMinion;
use App\StorableEvents\SideQuestMinionKillsHero;
use App\StorableEvents\HeroTakesDamageFromSideQuestMinion;
use Spatie\EventSourcing\Projectors\ProjectsEvents;
use Spatie\EventSourcing\Projectors\QueuedProjector;

final class HeroCombatStatsProjector implements QueuedProjector
{
    use ProjectsEvents;

    public function onHeroDealsDamageToSideQuestMinion(HeroDealsDamageToSideQuestMinion $event, string $aggregateUuid)
    {
        $hero = Hero::findUuidOrFail($aggregateUuid);
        $hero->damage_dealt += $event->damage;
        $hero->minion_damage_dealt += $event->damage;
        $hero->side_quest_damage_dealt += $event->damage;
        $hero->save();
    }

    public function onHeroKillsSideQuestMinion(HeroKillsSideQuestMinion $event, string $aggregateUuid)
    {
        $hero = Hero::findUuidOrFail($aggregateUuid);
        $hero->minion_kills++;
        $hero->combat_kills++;
        $hero->side_quest_kills++;
        $hero->save();
    }

    public function onHeroTakesDamageFromSideQuestMinion(HeroTakesDamageFromSideQuestMinion $event, string $aggregateUuid)
    {
        $hero = Hero::findUuidOrFail($aggregateUuid);
        $hero->damage_taken += $event->damage;
        $hero->minion_damage_taken += $event->damage;
        $hero->side_quest_damage_taken += $event->damage;
        $hero->save();
    }

    public function onHeroSideQuestDeath(SideQuestMinionKillsHero $event, string $aggregateUuid)
    {
        $hero = Hero::findUuidOrFail($aggregateUuid);
        $hero->side_quest_deaths++;
        $hero->minion_deaths++;
        $hero->combat_deaths++;
        $hero->save();
    }
}
