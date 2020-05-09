<?php

namespace App\Aggregates;

use App\Domain\Models\Hero;
use App\Domain\Models\Minion;
use App\StorableEvents\HeroCreated;
use App\StorableEvents\HeroDealsDamageToMinion;
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

    public function updatePlayerSpirit(int $playerSpiritID = null)
    {
        $this->recordThat(new UpdateHeroPlayerSpirit($playerSpiritID));

        return $this;
    }

    public function clearWeeklyPlayerSpirit(int $playerSpiritID)
    {
        $this->recordThat(new WeeklyPlayerSpiritClearedFromHero($playerSpiritID));

        return $this;
    }

    public function dealDamageToMinion(int $damage, Minion $minion)
    {
        $this->recordThat(new HeroDealsDamageToMinion($damage, $minion));
        return $this;
    }
}
