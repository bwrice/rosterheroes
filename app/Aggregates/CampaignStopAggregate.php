<?php

namespace App\Aggregates;

use App\StorableEvents\CampaignStopCreated;
use App\StorableEvents\SkirmishRemovedFromCampaignStop;
use App\StorableEvents\SkirmishAddedToCampaignStop;
use Spatie\EventSourcing\AggregateRoot;

final class CampaignStopAggregate extends AggregateRoot
{

    public function createCampaignStop(int $campaignID, int $questID, int $provinceID)
    {
        $this->recordThat(new CampaignStopCreated($campaignID, $questID, $provinceID));
        return $this;
    }

    public function addSkirmish(int $skirmishID)
    {
        $this->recordThat(new SkirmishAddedToCampaignStop($skirmishID));
        return $this;
    }

    public function removeSkirmish(int $skirmishID)
    {
        $this->recordThat(new SkirmishRemovedFromCampaignStop($skirmishID));
        return $this;
    }
}
