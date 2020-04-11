<?php

namespace App\Aggregates;

use App\StorableEvents\CampaignStopCreated;
use App\StorableEvents\CampaignStopDeleted;
use Spatie\EventSourcing\AggregateRoot;

final class CampaignStopAggregate extends AggregateRoot
{

    public function createCampaignStop(int $campaignID, int $questID, int $provinceID)
    {
        $this->recordThat(new CampaignStopCreated($campaignID, $questID, $provinceID));
        return $this;
    }

    public function deleteCampaignStop()
    {
        $this->recordThat(new CampaignStopDeleted());
        return $this;
    }
}
