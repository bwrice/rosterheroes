<?php

namespace App\Aggregates;

use App\Domain\Models\Minion;
use App\Domain\Models\SideQuest;
use App\StorableEvents\SideQuestMinionKillsSquadMember;
use App\StorableEvents\SpellAddedToLibrary;
use App\StorableEvents\SquadDealsDamageToSideQuestMinion;
use App\StorableEvents\SquadDefeatedInSideQuest;
use App\StorableEvents\SquadExperienceIncreased;
use App\StorableEvents\SquadKillsSideQuestMinion;
use App\StorableEvents\SquadMemberBlocksSideQuestMinion;
use App\StorableEvents\SquadTakesDamageFromSideQuestMinion;
use App\StorableEvents\SquadVictoriousInSideQuest;
use Spatie\EventSourcing\AggregateRoot;

final class SquadAggregate extends AggregateRoot
{

    public function increaseExperience(int $amount)
    {
        $this->recordThat(new SquadExperienceIncreased($amount));
        return $this;
    }

    public function killsSideQuestMinion(Minion $minion)
    {
        $this->recordThat(new SquadKillsSideQuestMinion($minion));
        return $this;
    }

    public function dealsDamageToSideQuestMinion(int $damage, Minion $minion)
    {
        $this->recordThat(new SquadDealsDamageToSideQuestMinion($damage, $minion));
        return $this;
    }

    public function takesDamageFromMinion(int $damage, Minion $minion)
    {
        $this->recordThat(new SquadTakesDamageFromSideQuestMinion($damage, $minion));
        return $this;
    }

    public function memberKilledFromSideQuestMinion(Minion $minion)
    {
        $this->recordThat(new SideQuestMinionKillsSquadMember($minion));
        return $this;
    }

    public function memberBlocksSideQuestMinion(Minion $minion)
    {
        $this->recordThat(new SquadMemberBlocksSideQuestMinion($minion));
        return $this;
    }

    public function sideQuestVictory(SideQuest $sideQuest)
    {
        $this->recordThat(new SquadVictoriousInSideQuest($sideQuest));
        return $this;
    }

    public function sideQuestDefeat(SideQuest $sideQuest)
    {
        $this->recordThat(new SquadDefeatedInSideQuest($sideQuest));
        return $this;
    }

}
