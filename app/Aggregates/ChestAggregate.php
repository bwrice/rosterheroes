<?php

namespace App\Aggregates;

use App\StorableEvents\ChestCreated;
use Spatie\EventSourcing\AggregateRoot;

final class ChestAggregate extends AggregateRoot
{
    public function createNewChest(int $quality, int $size, int $gold, int $squadID, int $chestBlueprintID = null)
    {
        $this->recordThat(new ChestCreated($quality, $size, $gold, $squadID, $chestBlueprintID));
        return $this;
    }
}
