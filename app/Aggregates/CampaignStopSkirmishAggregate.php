<?php

namespace App\Aggregates;

use App\CampaignStopSkirmish;
use App\StorableEvents\CampaignStopSkirmishCreated;
use Spatie\EventSourcing\AggregateRoot;

final class CampaignStopSkirmishAggregate extends AggregateRoot
{
    public function createCampaignStopSkirmish(int $campaignStopID, int $skirmishID)
    {
        $this->recordThat(new CampaignStopSkirmishCreated($campaignStopID, $skirmishID));
        return $this;
    }
}
