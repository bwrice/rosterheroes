<?php

namespace App\Projectors;

use App\Domain\Models\Squad;
use App\StorableEvents\SideQuestMinionKillsSquadMember;
use App\StorableEvents\SquadDealsDamageToSideQuestMinion;
use App\StorableEvents\SquadKillsSideQuestMinion;
use App\StorableEvents\SquadTakesDamageFromSideQuestMinion;
use Spatie\EventSourcing\Projectors\ProjectsEvents;
use Spatie\EventSourcing\Projectors\QueuedProjector;

final class SquadCombatStatsProjector implements QueuedProjector
{
    use ProjectsEvents;

    public function onSquadDealsDamageToMinion(SquadDealsDamageToSideQuestMinion $event, string $aggregateUuid)
    {
        $squad = Squad::findUuidOrFail($aggregateUuid);
        $squad->damage_dealt += $event->damage;
        $squad->minion_damage_dealt += $event->damage;
        $squad->side_quest_damage_dealt += $event->damage;
        $squad->save();
    }

    public function onSquadKillsMinion(SquadKillsSideQuestMinion $event, string $aggregateUuid)
    {
        $squad = Squad::findUuidOrFail($aggregateUuid);
        $squad->minion_kills++;
        $squad->side_quest_kills++;
        $squad->combat_kills++;
        $squad->save();
    }

    public function onSquadTakesDamageFromMinion(SquadTakesDamageFromSideQuestMinion $event, string $aggregateUuid)
    {
        $squad = Squad::findUuidOrFail($aggregateUuid);
        $squad->damage_taken += $event->damage;
        $squad->minion_damage_taken += $event->damage;
        $squad->side_quest_damage_taken += $event->damage;
        $squad->save();
    }

    public function onSideQuestMinionKillsSquadMember(SideQuestMinionKillsSquadMember $event, string $aggregateUuid)
    {
        $squad = Squad::findUuidOrFail($aggregateUuid);
        $squad->side_quest_deaths++;
        $squad->minion_deaths++;
        $squad->combat_deaths++;
        $squad->save();
    }
}
