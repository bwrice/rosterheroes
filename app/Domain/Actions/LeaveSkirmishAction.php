<?php


namespace App\Domain\Actions;


use App\Domain\Models\CampaignStop;
use App\Domain\Models\Skirmish;

class LeaveSkirmishAction extends CampaignStopAction
{
    public function execute(CampaignStop $campaignStop, Skirmish $skirmish)
    {
        $this->setProperties($campaignStop, $skirmish);

        $this->validateWeek();
        $this->validateQuestMatches();
    }
}
