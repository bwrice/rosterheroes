<?php


namespace App\Domain\Actions;


use App\Domain\Models\CampaignStop;
use App\Domain\Models\Skirmish;
use App\Exceptions\CampaignStopException;

class LeaveSkirmishAction extends CampaignStopAction
{
    public function execute(CampaignStop $campaignStop, Skirmish $skirmish)
    {
        $this->setProperties($campaignStop, $skirmish);

        $this->validateWeek();
        $this->validateQuestMatches();
        $this->validateCampaignStopHasSkirmish();

        $campaignStop->getAggregate()->removeSkirmish($skirmish->id)->persist();
    }

    protected function validateCampaignStopHasSkirmish()
    {
        $match = $this->campaignStop->skirmishes()->where('id', '=', $this->skirmish->id)->first();
        if (is_null($match)) {
            $message = "Can't leave skirmish that wasn't added";
            throw (new CampaignStopException($message, CampaignStopException::CODE_SKIRMISH_NOT_ADDED))
                ->setSkirmish($this->skirmish)
                ->setCampaignStop($this->campaignStop);
        }
    }
}
