<?php

namespace App\Aggregates;

use App\Domain\Models\Hero;
use App\StorableEvents\HeroCreated;
use App\StorableEvents\HeroSlotCreated;
use App\StorableEvents\UpdateHeroPlayerSpirit;
use Spatie\EventProjector\AggregateRoot;

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
}
