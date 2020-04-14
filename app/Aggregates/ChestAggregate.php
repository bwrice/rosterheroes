<?php

namespace App\Aggregates;

use App\StorableEvents\ChestCreated;
use Spatie\EventSourcing\AggregateRoot;

final class ChestAggregate extends AggregateRoot
{
    public function createNewChest(int $qualityTier, int $sizeTier, int $gold, int $squadID, int $chestBlueprintID = null)
    {
        $this->recordThat(new ChestCreated($qualityTier, $sizeTier, $gold, $squadID, $chestBlueprintID));
        return $this;
    }
}
