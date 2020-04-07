<?php

namespace App\Aggregates;

use App\Domain\Models\PlayerSpirit;
use App\StorableEvents\PlayerSpiritCreated;
use Spatie\EventSourcing\AggregateRoot;

final class PlayerSpiritAggregate extends AggregateRoot
{
    public function createPlayerSpirit(int $weekID, int $playerGameLogID, int $essenceCost, int $energy = PlayerSpirit::STARTING_ENERGY)
    {
        $this->recordThat(new PlayerSpiritCreated($weekID, $playerGameLogID, $essenceCost, $energy));

        return $this;
    }
}
