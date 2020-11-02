<?php

namespace App\Projectors;

use App\Domain\Models\Hero;
use App\StorableEvents\HeroBlocksSideQuestMinion;
use App\StorableEvents\HeroDealsDamageToSideQuestMinion;
use App\StorableEvents\HeroKillsSideQuestMinion;
use App\StorableEvents\SideQuestMinionKillsHero;
use App\StorableEvents\HeroTakesDamageFromSideQuestMinion;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

final class HeroCombatStatsProjector extends Projector implements ShouldQueue
{

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

    public function onHeroBlocksSideQuestMinion(HeroBlocksSideQuestMinion $event, string $aggregateUuid)
    {
        $hero = Hero::findUuidOrFail($aggregateUuid);
        $hero->attacks_blocked++;
        $hero->save();
    }
}
