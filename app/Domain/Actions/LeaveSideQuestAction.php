<?php


namespace App\Domain\Actions;


use App\Domain\Models\CampaignStop;
use App\Domain\Models\SideQuest;
use App\Exceptions\CampaignStopException;

class LeaveSideQuestAction extends CampaignStopAction
{
    public function execute(CampaignStop $campaignStop, SideQuest $skirmish)
    {
        $this->setProperties($campaignStop, $skirmish);

        $this->validateWeek();
        $this->validateQuestMatches();
        $this->validateCampaignStopHasSkirmish();

        $campaignStop->getAggregate()->removeSkirmish($skirmish->id)->persist();
    }

    protected function validateCampaignStopHasSkirmish()
    {
        $match = $this->campaignStop->sideQuests()->where('id', '=', $this->sideQuest->id)->first();
        if (is_null($match)) {
            $message = "Can't leave side quest that wasn't joined";
            throw (new CampaignStopException($message, CampaignStopException::CODE_SIDE_QUEST_NOT_ADDED))
                ->setSideQuest($this->sideQuest)
                ->setCampaignStop($this->campaignStop);
        }
    }
}
