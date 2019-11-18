<?php


namespace App\Exceptions;


use App\Domain\Models\Quest;
use App\Domain\Models\Squad;

class CampaignException extends \RuntimeException
{
    public const CODE_WEEK_LOCKED = 1;
    public const CODE_SQUAD_NOT_IN_QUEST_PROVINCE = 2;
    public const CODE_DIFFERENT_CONTINENT = 3;
    public const CODE_MAX_QUESTS_REACHED = 4;
    public const CODE_ALREADY_ENLISTED = 5;

    /** @var Squad|null */
    protected $squad;

    /** @var Quest|null */
    protected $quest;

    /**
     * @param Squad|null $squad
     * @return CampaignException
     */
    public function setSquad(?Squad $squad): CampaignException
    {
        $this->squad = $squad;
        return $this;
    }

    /**
     * @return Squad|null
     */
    public function getSquad(): ?Squad
    {
        return $this->squad;
    }

    /**
     * @param Quest|null $quest
     * @return CampaignException
     */
    public function setQuest(?Quest $quest): CampaignException
    {
        $this->quest = $quest;
        return $this;
    }

    /**
     * @return Quest|null
     */
    public function getQuest(): ?Quest
    {
        return $this->quest;
    }


}
