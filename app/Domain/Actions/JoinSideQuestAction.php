<?php


namespace App\Domain\Actions;


use App\Aggregates\CampaignStopAggregate;
use App\Domain\Models\Campaign;
use App\Domain\Models\CampaignStop;
use App\Domain\Models\SideQuest;
use App\Domain\Models\Week;
use App\Exceptions\CampaignException;
use App\Exceptions\CampaignStopException;

class JoinSideQuestAction extends CampaignStopAction
{
    /** @var Week */
    protected $week;

    /** @var CampaignStop */
    protected $campaignStop;

    /** @var SideQuest */
    protected $sideQuest;

    public function execute(CampaignStop $campaignStop, SideQuest $sideQuest)
    {
        $this->setProperties($campaignStop, $sideQuest);
        $this->validateWeek();
        $this->validateQuestMatches();
        $this->validateNonDuplicateSkirmish();
        $this->validateSquadLocation();
        $this->validateMaxSkirmishCount();

        /** @var CampaignStopAggregate $aggregate */
        $aggregate = CampaignStopAggregate::retrieve($campaignStop->uuid);
        $aggregate->addSkirmish($sideQuest->id)->persist();
    }

    protected function validateNonDuplicateSkirmish()
    {
        $skirmishIDs = $this->campaignStop->sideQuests->map(function (SideQuest $sideQuest) {
            return $sideQuest->id;
        })->values()->toArray();

        if (in_array($this->sideQuest->id, $skirmishIDs)) {
            throw (new CampaignStopException("Skirmish already added", CampaignStopException::CODE_SIDE_QUEST_ALREADY_ADDED))
                ->setCampaignStop($this->campaignStop)
                ->setSideQuest($this->sideQuest);
        }
    }

    protected function validateSquadLocation()
    {
        $squad = $this->campaignStop->campaign->squad;
        $quest = $this->sideQuest->quest;
        if ($this->campaignStop->campaign->squad->province_id !== $this->sideQuest->quest->province_id) {
            $message = $squad->name . " must be at province: " . $quest->province->name . " to add skirmish";
            throw (new CampaignStopException($message, CampaignStopException::CODE_SQUAD_NOT_IN_QUEST_PROVINCE))
                ->setCampaignStop($this->campaignStop)
                ->setSideQuest($this->sideQuest);
        }
    }

    protected function validateMaxSkirmishCount()
    {
        if ($this->campaignStop->sideQuests()->count() >= $this->campaignStop->campaign->squad->getSideQuestsPerQuest()) {
            $message = "Skirmish limit reached for " . $this->campaignStop->quest->name;
            throw (new CampaignStopException($message, CampaignStopException::CODE_SIDE_QUEST_LIMIT_REACHED))
                ->setCampaignStop($this->campaignStop)
                ->setSideQuest($this->sideQuest);
        }
    }
}
