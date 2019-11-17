<?php


namespace App\Domain\Models\Support\Squads;


use App\Domain\Collections\SkirmishCollection;
use App\Domain\Models\Campaign;
use App\Domain\Models\Quest;

class CampaignStop
{
    /**
     * @var Campaign
     */
    private $campaign;
    /**
     * @var Quest
     */
    private $quest;
    /**
     * @var SkirmishCollection
     */
    private $skirmishes;

    public function __construct(Campaign $campaign, Quest $quest, SkirmishCollection $skirmishes)
    {
        $this->campaign = $campaign;
        $this->quest = $quest;
        $this->skirmishes = $skirmishes;
    }

    /**
     * @return Campaign
     */
    public function getCampaign(): Campaign
    {
        return $this->campaign;
    }

    /**
     * @return Quest
     */
    public function getQuest(): Quest
    {
        return $this->quest;
    }

    /**
     * @return SkirmishCollection
     */
    public function getSkirmishes(): SkirmishCollection
    {
        return $this->skirmishes;
    }
}
