<?php


namespace App\Domain\Actions;


use App\Domain\Models\Campaign;
use App\Domain\Models\CampaignStop;
use App\Domain\Models\Skirmish;
use App\Domain\Models\Week;
use App\Exceptions\CampaignException;
use App\Exceptions\CampaignStopException;

class AddSkirmishToCampaignStopAction
{
    /** @var Week */
    protected $week;

    /** @var CampaignStop */
    protected $campaignStop;

    /** @var Skirmish */
    protected $skirmish;

    public function execute(CampaignStop $campaignStop, Skirmish $skirmish)
    {
        $this->week = Week::current();
        $this->campaignStop = $campaignStop;
        $this->skirmish = $skirmish;
        $this->validateWeek();
        $this->validateQuestMatches();
        $this->validateSquadLocation();
        $this->validateMaxSkirmishCount();
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
            throw (new CampaignStopException("Skirmish does not belong to the quest", CampaignStopException::CODE_INVALID_SKIRMISH))
                ->setCampaignStop($this->campaignStop)
                ->setSkirmish($this->skirmish);
        }
    }

    protected function validateSquadLocation()
    {
        $squad = $this->campaignStop->campaign->squad;
        $quest = $this->skirmish->quest;
        if ($this->campaignStop->campaign->squad->province_id !== $this->skirmish->quest->province_id) {
            $message = $squad->name . " must be at province: " . $quest->province->name . " to add skirmish";
            throw (new CampaignStopException($message, CampaignStopException::CODE_SQUAD_NOT_IN_QUEST_PROVINCE))
                ->setCampaignStop($this->campaignStop)
                ->setSkirmish($this->skirmish);
        }
    }

    protected function validateMaxSkirmishCount()
    {
        if ($this->campaignStop->skirmishes()->count() >= $this->campaignStop->campaign->squad->getSkirmishesPerQuest()) {
            $message = "Skirmish limit reached for " . $this->campaignStop->quest->name;
            throw (new CampaignStopException($message, CampaignStopException::CODE_SKIRMISH_LIMIT_REACHED))
                ->setCampaignStop($this->campaignStop)
                ->setSkirmish($this->skirmish);
        }
    }
}
