<?php

namespace App\Projectors;

use App\CampaignStopSkirmish;
use App\StorableEvents\CampaignStopSkirmishCreated;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

final class CampaignStopSkirmishProjector implements Projector
{
    use ProjectsEvents;

    public function onEventHappened(CampaignStopSkirmishCreated $event, string $aggregateUuid)
    {
        CampaignStopSkirmish::query()->create([
            'uuid' => $aggregateUuid,
            'campaign_stop_id' => $event->campaignStopID,
            'skirmish_id' => $event->skirmishID
        ]);
    }
}
