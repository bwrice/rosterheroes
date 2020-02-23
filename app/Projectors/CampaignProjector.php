<?php

namespace App\Projectors;

use App\Domain\Models\Campaign;
use App\Domain\Models\Squad;
use App\StorableEvents\CampaignCreated;
use App\StorableEvents\CampaignDeleted;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

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

    public function onCampaignDeleted(CampaignDeleted $event, string $aggregateUuid)
    {
        $campaign = Campaign::findUuidOrFail($aggregateUuid);
        $campaign->delete();
    }
}
