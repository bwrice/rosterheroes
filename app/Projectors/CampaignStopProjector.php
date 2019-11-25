<?php

namespace App\Projectors;

use App\Domain\Models\CampaignStop;
use App\StorableEvents\CampaignStopCreated;
use App\StorableEvents\CampaignStopDeleted;
use App\StorableEvents\SkirmishRemovedFromCampaignStop;
use App\StorableEvents\SkirmishAddedToCampaignStop;
use Spatie\EventSourcing\Projectors\Projector;
use Spatie\EventSourcing\Projectors\ProjectsEvents;

final class CampaignStopProjector implements Projector
{
    use ProjectsEvents;

    public function onCampaignStopCreated(CampaignStopCreated $event, string $aggregateUuid)
    {
        CampaignStop::query()->create([
            'uuid' => $aggregateUuid,
            'campaign_id' => $event->campaignID,
            'quest_id' => $event->questID,
            'province_id' => $event->provinceID
        ]);
    }
    public function onCampaignStopDeleted(CampaignStopDeleted $event, string $aggregateUuid)
    {
        $campaignStop = CampaignStop::findUuidOrFail($aggregateUuid);
        $campaignStop->delete();
    }

    public function onSkirmishAddedToCampaignStop(SkirmishAddedToCampaignStop $event, string $aggregateUuid)
    {
        $campaignStop = CampaignStop::findUuidOrFail($aggregateUuid);
        $campaignStop->skirmishes()->attach($event->skirmishID);
    }

    public function onSkirmishRemovedFromCampaignStop(SkirmishRemovedFromCampaignStop $event, string $aggregateUuid)
    {
        $campaignStop = CampaignStop::findUuidOrFail($aggregateUuid);
        $campaignStop->skirmishes()->detach($event->skirmishID);
    }
}
