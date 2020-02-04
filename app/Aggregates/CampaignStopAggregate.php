<?php

namespace App\Aggregates;

use App\Domain\Models\CampaignStop;
use App\StorableEvents\CampaignStopCreated;
use App\StorableEvents\CampaignStopDeleted;
use App\StorableEvents\SideQuestRemovedFromCampaignStop;
use App\StorableEvents\SideQuestAddedToCampaignStop;
use Spatie\EventSourcing\AggregateRoot;

final class CampaignStopAggregate extends AggregateRoot
{

    public function createCampaignStop(int $campaignID, int $questID, int $provinceID)
    {
        $this->recordThat(new CampaignStopCreated($campaignID, $questID, $provinceID));
        return $this;
    }

    public function addSideQuest(int $sideQuestID)
    {
        $this->recordThat(new SideQuestAddedToCampaignStop($sideQuestID));
        return $this;
    }

    public function removeSideQuest(int $sideQuestID)
    {
        $this->recordThat(new SideQuestRemovedFromCampaignStop($sideQuestID));
        return $this;
    }

    public function deleteCampaignStop()
    {
        $this->recordThat(new CampaignStopDeleted());
        return $this;
    }
}
