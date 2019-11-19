<?php

namespace App\Projectors;

use App\Domain\Models\Campaign;
use App\Domain\Models\Squad;
use App\StorableEvents\CampaignCreated;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;

class CampaignProjector implements Projector
{
    use ProjectsEvents;

    public function onCampaignCreated(CampaignCreated $event, string $aggregateUuid)
    {
        Campaign::query()->create([
            'uuid' => $aggregateUuid,
            'squad_id' => $event->squadID,
            'week_id' => $event->weekID,
            'continent_id' => $event->continentID
        ]);
    }
}
