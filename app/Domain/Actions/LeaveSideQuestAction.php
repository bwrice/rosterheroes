<?php


namespace App\Domain\Actions;


use App\Domain\Models\CampaignStop;
use App\Domain\Models\SideQuest;
use App\Exceptions\CampaignStopException;

class LeaveSideQuestAction extends CampaignStopAction
{
    public function execute(CampaignStop $campaignStop, SideQuest $sideQuest)
    {
        $this->setProperties($campaignStop, $sideQuest);

        $this->validateWeek();
        $this->validateQuestMatches();

        $sideQuestResult = $this->campaignStop->sideQuestResults()->where('side_quest_id', '=', $this->sideQuest->id)->first();
        if (is_null($sideQuestResult)) {
            $message = "No Side Quest found to leave";
            throw (new CampaignStopException($message, CampaignStopException::CODE_SIDE_QUEST_NOT_ADDED))
                ->setSideQuest($this->sideQuest)
                ->setCampaignStop($this->campaignStop);
        }

        $sideQuestResult->delete();
    }
}
