<?php

namespace App\Aggregates;

use App\Domain\Models\Hero;
use App\StorableEvents\HeroCreated;
use App\StorableEvents\HeroSlotCreated;
use App\StorableEvents\MeasurableCreated;
use Spatie\EventProjector\AggregateRoot;

final class HeroAggregate extends AggregateRoot
{
    public function createHero(string $name, int $heroClassID, int $heroRaceID, int $heroRankID)
    {
        $this->recordThat(new HeroCreated($name, $heroClassID, $heroRaceID, $heroRankID));

        return $this;
    }

    public function createHeroSlot(int $slotTypeID)
    {
        $this->recordThat(new HeroSlotCreated($slotTypeID));

        return $this;
    }
}
