<?php

namespace App\Aggregates;

use App\StorableEvents\CampaignCreated;
use App\StorableEvents\CampaignDeleted;
use Spatie\EventSourcing\AggregateRoot;

final class CampaignAggregate extends AggregateRoot
{
    public function createCampaign(int $squadID, int $weekID, int $continentID)
    {
        $this->recordThat(new CampaignCreated($squadID, $weekID, $continentID));
        return $this;
    }

    public function deleteCampaign()
    {
        $this->recordThat(new CampaignDeleted());
        return $this;
    }
}
