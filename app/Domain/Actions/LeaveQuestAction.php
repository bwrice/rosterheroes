<?php


namespace App\Domain\Actions;


use App\Domain\Models\CampaignStop;
use App\Domain\Models\Quest;
use App\Domain\Models\Squad;
use App\Exceptions\CampaignException;

class LeaveQuestAction extends SquadQuestAction
{
    /** @var CampaignStop */
    protected $campaignStop;

    public function execute(Squad $squad, Quest $quest)
    {
        $this->setProperties($squad, $quest);
        $this->validateWeek();
        $this->campaign = $this->squad->getCurrentCampaign();
        $this->validateCampaign();
    }

    protected function validateCampaign(): void
    {
        if (! $this->campaign) {
            throw (new CampaignException("No campaign exists for this week", CampaignException::CODE_NO_CURRENT_CAMPAIGN))
                ->setQuest($this->quest)
                ->setSquad($this->squad);
        }

        $this->campaignStop = $this->campaign->campaignStops()->forQuest($this->quest->id)->first();
        if (! $this->campaignStop) {
            throw (new CampaignException("No campaign exists for this week", CampaignException::CODE_QUEST_NOT_IN_CAMPAIGN))
                ->setQuest($this->quest)
                ->setSquad($this->squad);
        }
    }

}
