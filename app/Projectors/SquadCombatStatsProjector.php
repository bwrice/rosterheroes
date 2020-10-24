<?php

namespace App\Projectors;

use App\Domain\Models\Squad;
use App\StorableEvents\SideQuestMinionKillsSquadMember;
use App\StorableEvents\SquadDealsDamageToSideQuestMinion;
use App\StorableEvents\SquadDefeatedInSideQuest;
use App\StorableEvents\SquadKillsSideQuestMinion;
use App\StorableEvents\SquadMemberBlocksSideQuestMinion;
use App\StorableEvents\SquadTakesDamageFromSideQuestMinion;
use App\StorableEvents\SquadVictoriousInSideQuest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

final class SquadCombatStatsProjector extends Projector implements ShouldQueue
{
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

    public function onSquadMemberBlocksSideQuestMinion(SquadMemberBlocksSideQuestMinion $event, string $aggregateUuid)
    {
        $squad = Squad::findUuidOrFail($aggregateUuid);
        $squad->attacks_blocked++;
        $squad->save();
    }

    public function onSquadVictoriousInSideQuest(SquadVictoriousInSideQuest $event, string $aggregateUuid)
    {
        $squad = Squad::findUuidOrFail($aggregateUuid);
        $squad->side_quest_victories++;
        $squad->save();
    }

    public function onSquadDefeatedInSideQuest(SquadDefeatedInSideQuest $event, string $aggregateUuid)
    {
        $squad = Squad::findUuidOrFail($aggregateUuid);
        $squad->side_quest_defeats++;
        $squad->save();
    }
}
