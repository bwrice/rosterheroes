<?php

namespace App\Projectors;

use App\Domain\Models\Province;
use App\StorableEvents\ProvinceCreated;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

final class ProvinceProjectory implements Projector
{
    use ProjectsEvents;

    public function onProvinceCreated(ProvinceCreated $event, $aggregateUuid)
    {
        Province::query()->create([
            'uuid' => $aggregateUuid,
            'name' => $event->name,
            'color' => $event->color,
            'vector_paths' => $event->vectorPaths,
            'continent_id' => $event->continentID,
            'territory_id' => $event->territoryID
        ]);
    }
}
