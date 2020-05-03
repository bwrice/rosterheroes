<?php

namespace App\Aggregates;

use App\StorableEvents\ChestCreated;
use Spatie\EventSourcing\AggregateRoot;

final class ChestAggregate extends AggregateRoot
{
    public function createNewChest(int $quality, int $size, int $gold, int $squadID, ?int $chestBlueprintID, ?string $description, string $sourceType = null, int $sourceID = null)
    {
        $this->recordThat(new ChestCreated($quality, $size, $gold, $squadID, $chestBlueprintID, $description, $sourceType, $sourceID));
        return $this;
    }
}
