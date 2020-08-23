<?php


namespace App\Domain\Actions;


use App\Domain\Models\CampaignStop;
use App\Domain\Models\SideQuest;
use App\Domain\Models\Week;
use App\Exceptions\CampaignStopException;
use App\Domain\Models\SideQuestResult;
use Illuminate\Support\Str;

class JoinSideQuestAction extends CampaignStopAction
{
    /** @var Week */
    protected $week;

    /** @var CampaignStop */
    protected $campaignStop;

    /** @var SideQuest */
    protected $sideQuest;

    /**
     * @param CampaignStop $campaignStop
     * @param SideQuest $sideQuest
     * @return SideQuestResult
     */
    public function execute(CampaignStop $campaignStop, SideQuest $sideQuest)
    {
        $this->setProperties($campaignStop, $sideQuest);
        $this->validateWeek();
        $this->validateQuestMatches();
        $this->validateNoExistingSideQuestResult();
        $this->validateSquadLocation();
        $this->validateMaxSideQuestCount();

        /** @var SideQuestResult $sideQuestResult */
        $sideQuestResult = SideQuestResult::query()->create([
            'uuid' => (string) Str::uuid(),
            'campaign_stop_id' => $campaignStop->id,
            'side_quest_id' => $sideQuest->id
        ]);

        return $sideQuestResult;
    }

    protected function validateNoExistingSideQuestResult()
    {
        $existingSideQuestResult = $this->campaignStop->sideQuestResults()->where('side_quest_id', '=', $this->sideQuest->id)->first();

        if ($existingSideQuestResult) {
            throw (new CampaignStopException("Side quest already joined", CampaignStopException::CODE_SIDE_QUEST_ALREADY_ADDED))
                ->setCampaignStop($this->campaignStop)
                ->setSideQuest($this->sideQuest);
        }
    }

    protected function validateSquadLocation()
    {
        $squad = $this->campaignStop->campaign->squad;
        $quest = $this->sideQuest->quest;
        if ($this->campaignStop->campaign->squad->province_id !== $this->sideQuest->quest->province_id) {
            $message = $squad->name . " must be at province: " . $quest->province->name . " to join side quest";
            throw (new CampaignStopException($message, CampaignStopException::CODE_SQUAD_NOT_IN_QUEST_PROVINCE))
                ->setCampaignStop($this->campaignStop)
                ->setSideQuest($this->sideQuest);
        }
    }

    protected function validateMaxSideQuestCount()
    {
        if ($this->campaignStop->sideQuests()->count() >= $this->campaignStop->campaign->squad->getSideQuestsPerQuest()) {
            $message = "Side quest limit reached for " . $this->campaignStop->quest->name;
            throw (new CampaignStopException($message, CampaignStopException::CODE_SIDE_QUEST_LIMIT_REACHED))
                ->setCampaignStop($this->campaignStop)
                ->setSideQuest($this->sideQuest);
        }
    }
}
