<?php


namespace App\Domain\Actions;


use App\Domain\Models\Campaign;
use App\Domain\Models\Quest;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Exceptions\CampaignException;

abstract class SquadQuestAction
{
    /** @var Squad */
    protected $squad;

    /** @var Quest */
    protected $quest;

    /** @var Week */
    protected $week;

    /** @var Campaign */
    protected $campaign;


    /**
     * @param Squad $squad
     * @param Quest $quest
     */
    protected function setProperties(Squad $squad, Quest $quest): void
    {
        $this->squad = $squad;
        $this->quest = $quest;
        $this->week = Week::current();
    }

    protected function validateWeek(): void
    {
        if (!$this->week->adventuringOpen()) {
            throw (new CampaignException("Week is currently locked", CampaignException::CODE_WEEK_LOCKED))
                ->setSquad($this->squad);
        }
    }
}
