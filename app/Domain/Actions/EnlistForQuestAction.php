<?php


namespace App\Domain\Actions;


use App\Domain\Models\Campaign;
use App\Domain\Models\Quest;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Exceptions\CampaignException;

class EnlistForQuestAction
{
    /** @var Squad */
    protected $squad;

    /** @var Quest */
    protected $quest;

    /** @var Week */
    protected $week;

    /** @var Campaign */
    protected $campaign;

    public function execute(Squad $squad, Quest $quest)
    {
        $this->setProperties($squad, $quest);
        $this->validateWeek();
        $this->validateQuestLocation();

        $campaign = $squad->getCurrentCampaign([
            'campaignStops',
            'continent'
        ]);

        if ($campaign) {
            $this->campaign = $campaign;
            $this->validateCampaign();
        }
    }

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

    protected function validateQuestLocation(): void
    {
        if ($this->squad->province_id !== $this->quest->province_id) {
            $message = $this->squad->name . " must be in the province of " . $this->quest->province->name . " to enlist";
            throw (new CampaignException($message, CampaignException::CODE_SQUAD_NOT_IN_QUEST_PROVINCE))
                ->setSquad($this->squad)
                ->setQuest($this->quest);
        }
    }

    protected function validateCampaign():void
    {
        if ($this->quest->province->continent_id !== $this->campaign->continent_id) {
            $message = 'Campaign already exists for different continent, ' . $this->campaign->continent->name;
            throw (new CampaignException($message, CampaignException::CODE_DIFFERENT_CONTINENT))
                ->setSquad($this->squad)
                ->setQuest($this->quest);
        }
    }
}
