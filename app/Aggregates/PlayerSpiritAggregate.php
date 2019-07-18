<?php

namespace App\Aggregates;

use App\Domain\Models\PlayerSpirit;
use App\StorableEvents\PlayerSpiritCreated;
use Spatie\EventProjector\AggregateRoot;

final class PlayerSpiritAggregate extends AggregateRoot
{
    public function createPlayerSpirit(int $weekID, int $playerID, int $gameID, int $essenceCost, int $energy = PlayerSpirit::STARTING_ENERGY, int $playerGameLogID = null)
    {
        $this->recordThat(new PlayerSpiritCreated($weekID, $playerID, $gameID, $essenceCost, $energy, $playerGameLogID));

        return $this;
    }
}
