<?php

namespace App\Projectors;

use App\Chest;
use App\StorableEvents\ChestCreated;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

final class ChestProjector implements Projector
{
    use ProjectsEvents;

    public function onChestCreated(ChestCreated $event, $aggregateUuid)
    {
        Chest::query()->create([
            'uuid' => $aggregateUuid,
            'squad_id' => $event->squadID,
            'chest_blueprint_id' => $event->chestBlueprintID,
            'gold' => $event->gold,
            'quality' => $event->quality,
            'size' => $event->size
        ]);
    }
}
