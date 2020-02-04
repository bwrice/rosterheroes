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
        $this->validateNonDuplicateSideQuest();
        $this->validateSquadLocation();
        $this->validateMaxSideQuestCount();

        /** @var CampaignStopAggregate $aggregate */
        $aggregate = CampaignStopAggregate::retrieve($campaignStop->uuid);
        $aggregate->addSideQuest($sideQuest->id)->persist();
    }

    protected function validateNonDuplicateSideQuest()
    {
        $sideQuestIDs = $this->campaignStop->sideQuests->map(function (SideQuest $sideQuest) {
            return $sideQuest->id;
        })->values()->toArray();

        if (in_array($this->sideQuest->id, $sideQuestIDs)) {
            throw (new CampaignStopException("Side quest already joined", CampaignStopException::CODE_SIDE_QUEST_ALREADY_ADDED))
                ->setCampaignStop($this->campaignStop)
                ->setSideQuest($this->sideQuest);
        }
    }

    protected function validateSquadLocation()
    {
        $squad = $this->campaignStop->campaign->squad;
        $quest = $this->sideQuest->quest;
        if ($this->campaignStop->campaign->squad->province_id !== $this->sideQuest->quest->province_id) {
            $message = $squad->name . " must be at province: " . $quest->province->name . " to join side quest";
            throw (new CampaignStopException($message, CampaignStopException::CODE_SQUAD_NOT_IN_QUEST_PROVINCE))
                ->setCampaignStop($this->campaignStop)
                ->setSideQuest($this->sideQuest);
        }
    }

    protected function validateMaxSideQuestCount()
    {
        if ($this->campaignStop->sideQuests()->count() >= $this->campaignStop->campaign->squad->getSideQuestsPerQuest()) {
            $message = "Side quest limit reached for " . $this->campaignStop->quest->name;
            throw (new CampaignStopException($message, CampaignStopException::CODE_SIDE_QUEST_LIMIT_REACHED))
                ->setCampaignStop($this->campaignStop)
                ->setSideQuest($this->sideQuest);
        }
    }
}
