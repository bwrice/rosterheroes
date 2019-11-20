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
    }

    protected function validateWeek()
    {
        if (! $this->week->adventuringOpen()) {
            throw (new CampaignStopException("Week is currently locked", CampaignException::CODE_WEEK_LOCKED))
                ->setCampaignStop($this->campaignStop)
                ->setSkirmish($this->skirmish);
        }
    }
}
