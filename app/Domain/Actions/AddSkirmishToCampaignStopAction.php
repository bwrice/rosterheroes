<?php


namespace App\Domain\Actions;


use App\Aggregates\CampaignStopAggregate;
use App\Domain\Models\Campaign;
use App\Domain\Models\CampaignStop;
use App\Domain\Models\SideQuest;
use App\Domain\Models\Week;
use App\Exceptions\CampaignException;
use App\Exceptions\CampaignStopException;

class AddSkirmishToCampaignStopAction extends CampaignStopAction
{
    /** @var Week */
    protected $week;

    /** @var CampaignStop */
    protected $campaignStop;

    /** @var SideQuest */
    protected $skirmish;

    public function execute(CampaignStop $campaignStop, SideQuest $skirmish)
    {
        $this->setProperties($campaignStop, $skirmish);
        $this->validateWeek();
        $this->validateQuestMatches();
        $this->validateNonDuplicateSkirmish();
        $this->validateSquadLocation();
        $this->validateMaxSkirmishCount();

        /** @var CampaignStopAggregate $aggregate */
        $aggregate = CampaignStopAggregate::retrieve($campaignStop->uuid);
        $aggregate->addSkirmish($skirmish->id)->persist();
    }

    protected function validateNonDuplicateSkirmish()
    {
        $skirmishIDs = $this->campaignStop->skirmishes->map(function (SideQuest $skirmish) {
            return $skirmish->id;
        })->values()->toArray();

        if (in_array($this->skirmish->id, $skirmishIDs)) {
            throw (new CampaignStopException("Skirmish already added", CampaignStopException::CODE_SKIRMISH_ALREADY_ADDED))
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
