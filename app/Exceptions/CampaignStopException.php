<?php


namespace App\Exceptions;


use App\Domain\Models\CampaignStop;
use App\Domain\Models\Skirmish;

class CampaignStopException extends CampaignException
{
    public const CODE_INVALID_SKIRMISH = 21;
    public const CODE_SKIRMISH_LIMIT_REACHED = 22;

    /** @var CampaignStop */
    protected $campaignStop;

    /** @var Skirmish */
    protected $skirmish;

    /**
     * @param CampaignStop $campaignStop
     * @return CampaignStopException
     */
    public function setCampaignStop(CampaignStop $campaignStop): CampaignStopException
    {
        $this->campaignStop = $campaignStop;
        return $this;
    }

    /**
     * @param Skirmish $skirmish
     * @return CampaignStopException
     */
    public function setSkirmish(Skirmish $skirmish): CampaignStopException
    {
        $this->skirmish = $skirmish;
        return $this;
    }
}
