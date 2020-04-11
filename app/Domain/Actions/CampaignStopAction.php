<?php


namespace App\Domain\Actions;


use App\Domain\Models\CampaignStop;
use App\Domain\Models\SideQuest;
use App\Domain\Models\Week;
use App\Exceptions\CampaignStopException;

abstract class CampaignStopAction
{
    /** @var Week */
    protected $week;

    /** @var CampaignStop */
    protected $campaignStop;

    /** @var SideQuest */
    protected $sideQuest;

    protected function setProperties(CampaignStop $campaignStop, SideQuest $sideQuest)
    {
        $this->week = Week::current();
        $this->campaignStop = $campaignStop;
        $this->sideQuest = $sideQuest;
    }

    protected function validateWeek()
    {
        if (! $this->week->adventuringOpen()) {
            throw (new CampaignStopException("Week is currently locked", CampaignStopException::CODE_WEEK_LOCKED))
                ->setCampaignStop($this->campaignStop)
                ->setSideQuest($this->sideQuest);
        }

        if ($this->campaignStop->campaign->week_id !== $this->week->id) {
            throw (new CampaignStopException("Cannot edit campaign for previous week", CampaignStopException::CODE_CAMPAIGN_FOR_PREVIOUS_WEEK))
                ->setCampaignStop($this->campaignStop)
                ->setSideQuest($this->sideQuest);
        }
    }

    protected function validateQuestMatches()
    {
        if ($this->sideQuest->quest_id !== $this->campaignStop->quest_id) {
            throw (new CampaignStopException("Side quest does not belong to the quest", CampaignStopException::CODE_QUEST_NON_MATCH))
                ->setCampaignStop($this->campaignStop)
                ->setSideQuest($this->sideQuest);
        }
    }
}
