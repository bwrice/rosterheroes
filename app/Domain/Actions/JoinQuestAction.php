<?php


namespace App\Domain\Actions;


use App\Domain\Models\Campaign;
use App\Domain\Models\CampaignStop;
use App\Domain\Models\Quest;
use App\Domain\Models\Squad;
use App\Exceptions\CampaignException;
use Illuminate\Support\Str;

class JoinQuestAction extends SquadQuestAction
{
    public function execute(Squad $squad, Quest $quest): CampaignStop
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
        } else {
            $campaign = Campaign::query()->create([
                'uuid' => (string) Str::uuid(),
                'squad_id' => $squad->id,
                'week_id' => $this->week->id,
                'continent_id' => $quest->province->continent_id
            ]);
        }

        /** @var CampaignStop $campaignStop */
        $campaignStop = CampaignStop::query()->create([
            'uuid' => (string) Str::uuid(),
            'campaign_id' => $campaign->id,
            'quest_id' => $quest->id,
            'province_id' => $quest->province_id
        ]);

        return $campaignStop;
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

        if (! ($this->campaign->campaignStops->count() < $this->squad->getQuestsPerWeek())) {
            $message = "Max quests per week reached";
            throw (new CampaignException($message, CampaignException::CODE_MAX_QUESTS_REACHED))
                ->setSquad($this->squad)
                ->setQuest($this->quest);
        }

        $matchingQuest = $this->campaign->campaignStops->first(function (CampaignStop $campaignStop) {
            return $campaignStop->quest_id === $this->quest->id;
        });
        if ($matchingQuest) {
            $message = $this->squad->name . " has already enlisted for " . $this->quest->name;
            throw (new CampaignException($message, CampaignException::CODE_ALREADY_ENLISTED))
                ->setSquad($this->squad)
                ->setQuest($this->quest);
        }
    }
}
