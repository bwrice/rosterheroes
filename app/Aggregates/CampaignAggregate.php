<?php

namespace App\Aggregates;

use App\StorableEvents\CampaignCreated;
use Spatie\EventProjector\AggregateRoot;

final class CampaignAggregate extends AggregateRoot
{
    public function createCampaign(int $squadID, int $weekID, int $continentID)
    {
        $this->recordThat(new CampaignCreated($squadID, $weekID, $continentID));

        return $this;
    }
}
