<?php


namespace App\Exceptions;


use App\Domain\Models\CampaignStop;
use App\Domain\Models\SideQuest;

class CampaignStopException extends CampaignException
{
    public const CODE_QUEST_NON_MATCH = 21;
    public const CODE_SKIRMISH_LIMIT_REACHED = 22;
    public const CODE_SKIRMISH_ALREADY_ADDED = 23;
    public const CODE_SKIRMISH_NOT_ADDED = 24;

    /** @var CampaignStop */
    protected $campaignStop;

    /** @var SideQuest */
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
     * @param SideQuest $skirmish
     * @return CampaignStopException
     */
    public function setSkirmish(SideQuest $skirmish): CampaignStopException
    {
        $this->skirmish = $skirmish;
        return $this;
    }
}
