<?php


namespace App\Domain\Actions;


use App\Domain\Models\CampaignStop;
use App\Domain\Models\Skirmish;
use App\Domain\Models\Week;
use App\Exceptions\CampaignStopException;

abstract class CampaignStopAction
{
    /** @var Week */
    protected $week;

    /** @var CampaignStop */
    protected $campaignStop;

    /** @var Skirmish */
    protected $skirmish;

    protected function setProperties(CampaignStop $campaignStop, Skirmish $skirmish)
    {
        $this->week = Week::current();
        $this->campaignStop = $campaignStop;
        $this->skirmish = $skirmish;
    }

    protected function validateWeek()
    {
        if (! $this->week->adventuringOpen()) {
            throw (new CampaignStopException("Week is currently locked", CampaignStopException::CODE_WEEK_LOCKED))
                ->setCampaignStop($this->campaignStop)
                ->setSkirmish($this->skirmish);
        }
    }

    protected function validateQuestMatches()
    {
        if ($this->skirmish->quest_id !== $this->campaignStop->quest_id) {
            throw (new CampaignStopException("Skirmish does not belong to the quest", CampaignStopException::CODE_QUEST_NON_MATCH))
                ->setCampaignStop($this->campaignStop)
                ->setSkirmish($this->skirmish);
        }
    }
}
