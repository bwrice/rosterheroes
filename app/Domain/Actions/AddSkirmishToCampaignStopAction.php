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
        $this->validateSkirmish();
    }

    protected function validateWeek()
    {
        if (! $this->week->adventuringOpen()) {
            throw (new CampaignStopException("Week is currently locked", CampaignStopException::CODE_WEEK_LOCKED))
                ->setCampaignStop($this->campaignStop)
                ->setSkirmish($this->skirmish);
        }
    }

    protected function validateSkirmish()
    {
        if ($this->skirmish->quest_id !== $this->campaignStop->quest_id) {
            throw (new CampaignStopException("Skirmish does not belong to Quest", CampaignStopException::CODE_INVALID_SKIRMISH))
                ->setCampaignStop($this->campaignStop)
                ->setSkirmish($this->skirmish);
        }
    }
}
