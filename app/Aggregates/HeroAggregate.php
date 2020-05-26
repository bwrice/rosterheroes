<?php

namespace App\Aggregates;

use App\Domain\Models\Hero;
use App\Domain\Models\Minion;
use App\StorableEvents\HeroBlocksSideQuestMinion;
use App\StorableEvents\HeroCreated;
use App\StorableEvents\HeroDealsDamageToSideQuestMinion;
use App\StorableEvents\HeroKillsSideQuestMinion;
use App\StorableEvents\HeroTakesDamageFromSideQuestMinion;
use App\StorableEvents\SideQuestMinionKillsHero;
use App\StorableEvents\UpdateHeroPlayerSpirit;
use App\StorableEvents\WeeklyPlayerSpiritClearedFromHero;
use Spatie\EventSourcing\AggregateRoot;

final class HeroAggregate extends AggregateRoot
{
    public function createHero(string $name, int $squadID, int $heroClassID, int $heroRaceID, int $heroRankID, int $combatPositionID)
    {
        $this->recordThat(new HeroCreated($name, $squadID, $heroClassID, $heroRaceID, $heroRankID, $combatPositionID));
        return $this;
    }

    public function clearWeeklyPlayerSpirit(int $playerSpiritID)
    {
        $this->recordThat(new WeeklyPlayerSpiritClearedFromHero($playerSpiritID));
        return $this;
    }

    public function dealDamageToSideQuestMinion(int $damage, Minion $minion)
    {
        $this->recordThat(new HeroDealsDamageToSideQuestMinion($damage, $minion));
        return $this;
    }

    public function killsSideQuestMinion(Minion $minion)
    {
        $this->recordThat(new HeroKillsSideQuestMinion($minion));
        return $this;
    }

    public function takesDamageFromSideQuestMinion(int $damage, Minion $minion)
    {
        $this->recordThat(new HeroTakesDamageFromSideQuestMinion($damage, $minion));
        return $this;
    }

    public function killedBySideQuestMinion(Minion $minion)
    {
        $this->recordThat(new SideQuestMinionKillsHero($minion));
        return $this;
    }

    public function blocksSideQuestMinion(Minion $minion)
    {
        $this->recordThat(new HeroBlocksSideQuestMinion($minion));
        return $this;
    }
}
