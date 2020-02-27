<?php

namespace App\Aggregates;

use App\Domain\Combat\CombatGroups\CombatSquad;
use App\Domain\Combat\CombatGroups\SideQuestGroup;
use App\SideQuestResult;
use App\StorableEvents\BattlefieldSetForSideQuest;
use App\StorableEvents\HeroBlocksMinionSideQuestEvent;
use App\StorableEvents\HeroDamagesMinionSideQuestEvent;
use App\StorableEvents\HeroKillsMinionSideQuestEvent;
use App\StorableEvents\MinionBlocksHeroSideQuestEvent;
use App\StorableEvents\MinionDamagesHeroSideQuestEvent;
use App\StorableEvents\MinionKillsHeroSideQuestEvent;
use Spatie\EventSourcing\AggregateRoot;

final class SideQuestEventAggregate extends AggregateRoot
{
    public function createHeroDamagesMinionEvent(
        int $sideQuestResultID,
        int $moment,
        string $heroUuid,
        string $attackUuid,
        string $itemUuid,
        string $minionUuid,
        int $damage,
        int $staminaCost,
        int $manaCost
    )
    {
        $this->recordThat(new HeroDamagesMinionSideQuestEvent($sideQuestResultID, $moment, $heroUuid, $attackUuid, $itemUuid, $minionUuid, $damage, $staminaCost, $manaCost));
        return $this;
    }

    public function createHeroKillsMinionEvent(
        int $sideQuestResultID,
        int $moment,
        string $heroUuid,
        string $attackUuid,
        string $itemUuid,
        string $minionUuid,
        int $damage,
        int $staminaCost,
        int $manaCost
    ) {
        $this->recordThat(new HeroKillsMinionSideQuestEvent($sideQuestResultID, $moment, $heroUuid, $attackUuid, $itemUuid, $minionUuid, $damage, $staminaCost, $manaCost));
        return $this;
    }

    public function createMinionBlocksHeroEvent(
        int $sideQuestResultID,
        int $moment,
        string $heroUuid,
        string $attackUuid,
        string $itemUuid,
        string $minionUuid,
        int $staminaCost,
        int $manaCost
    )
    {
        $this->recordThat(new MinionBlocksHeroSideQuestEvent($sideQuestResultID, $moment, $heroUuid, $attackUuid, $itemUuid, $minionUuid, $staminaCost, $manaCost));
        return $this;
    }

    public function createMinionDamagesHeroEvent(
        int $sideQuestResultID,
        int $moment,
        array $data)
    {
        $this->recordThat(new MinionDamagesHeroSideQuestEvent($sideQuestResultID, $moment, $data));
        return $this;
    }

    public function createMinionKillsHeroEvent(
        int $sideQuestResultID,
        int $moment,
        array $data)
    {
        $this->recordThat(new MinionKillsHeroSideQuestEvent($sideQuestResultID, $moment, $data));
        return $this;
    }

    public function createHeroBlocksMinionEvent(
        int $sideQuestResultID,
        int $moment,
        array $data)
    {
        $this->recordThat(new HeroBlocksMinionSideQuestEvent($sideQuestResultID, $moment, $data));
        return $this;
    }

    public function createBattlefieldSetEvent(int $sideQuestResultID, array $eventData)
    {
        $this->recordThat(new BattlefieldSetForSideQuest($sideQuestResultID, $eventData));
        return $this;
    }
}
