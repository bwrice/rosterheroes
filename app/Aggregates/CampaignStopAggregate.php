<?php

namespace App\Aggregates;

use App\StorableEvents\CampaignStopCreated;
use Spatie\EventProjector\AggregateRoot;

final class CampaignStopAggregate extends AggregateRoot
{

    public function createCampaignStop(int $campaignID, int $questID, int $provinceID)
    {
        $this->recordThat(New CampaignStopCreated($campaignID, $questID, $provinceID));

        return $this;
    }
}
