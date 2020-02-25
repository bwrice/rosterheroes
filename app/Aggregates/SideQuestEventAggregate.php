<?php

namespace App\Aggregates;

use App\StorableEvents\HeroDamagesMinionSideQuestEvent;
use App\StorableEvents\HeroKillsMinionSideQuestEvent;
use App\StorableEvents\MinionBlocksHeroSideQuestEvent;
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
        int $damage
    )
    {
        $this->recordThat(new HeroDamagesMinionSideQuestEvent($sideQuestResultID, $moment, $heroUuid, $attackUuid, $itemUuid, $minionUuid, $damage));
        return $this;
    }

    public function createHeroKillsMinionEvent(
        int $sideQuestResultID,
        int $moment,
        string $heroUuid,
        string $attackUuid,
        string $itemUuid,
        string $minionUuid,
        int $damage
    ) {
        $this->recordThat(new HeroKillsMinionSideQuestEvent($sideQuestResultID, $moment, $heroUuid, $attackUuid, $itemUuid, $minionUuid, $damage));
        return $this;
    }

    public function createMinionBlocksHeroEvent(
        int $sideQuestResultID,
        int $moment,
        string $heroUuid,
        string $attackUuid,
        string $itemUuid,
        string $minionUuid
    )
    {
        $this->recordThat(new MinionBlocksHeroSideQuestEvent($sideQuestResultID, $moment, $heroUuid, $attackUuid, $itemUuid, $minionUuid));
        return $this;
    }
}
