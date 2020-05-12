<?php


namespace App\Domain\Actions\Testing;


use App\Domain\Actions\JoinQuestAction;
use App\Domain\Actions\JoinSideQuestAction;
use App\Domain\Actions\SquadBorderTravelAction;
use App\Domain\Collections\ProvinceCollection;
use App\Domain\Collections\QuestCollection;
use App\Domain\Models\CampaignStop;
use App\Domain\Models\Province;
use App\Domain\Models\Quest;
use App\Domain\Models\Squad;
use Illuminate\Support\Facades\DB;

class AutoManageCampaign
{
    /**
     * @var SquadBorderTravelAction
     */
    protected $borderTravelAction;
    /**
     * @var JoinQuestAction
     */
    protected $joinQuestAction;
    /**
     * @var JoinSideQuestAction
     */
    protected $joinSideQuestAction;

    /** @var Squad */
    protected $squad;

    /** @var int */
    protected $continentID;

    /** @var QuestCollection */
    protected $joinedQuests;

    /** @var ProvinceCollection */
    protected $visitedProvinces;

    protected $questsPerWeek;

    protected $sideQuestsPerQuest;

    public function __construct(
        SquadBorderTravelAction $borderTravelAction,
        JoinQuestAction $joinQuestAction,
        JoinSideQuestAction $joinSideQuestAction)
    {
        $this->borderTravelAction = $borderTravelAction;
        $this->joinQuestAction = $joinQuestAction;
        $this->joinSideQuestAction = $joinSideQuestAction;
    }

    public function execute(Squad $squad)
    {
        $this->squad = $squad;
        $this->continentID = $squad->province->continent_id;
        $this->visitedProvinces = new ProvinceCollection([$squad->province]);
        $this->questsPerWeek = $squad->getQuestsPerWeek();
        $this->sideQuestsPerQuest = $squad->getSideQuestsPerQuest();
        $this->joinedQuests = $this->getInitialJoinedQuests();



        DB::transaction(function () {
            foreach (range (1, $this->questsPerWeek) as $questCount) {

                $search = true;
                $loopCount = 1;

                while($search) {

                    $foundQuest = $this->joinNewQuestAndSideQuests();

                    /*
                     * Border travel if we didn't just join our last needed quest
                     */
                    if ($this->questsPerWeek != $loopCount && !$foundQuest) {
                        $this->borderTravelWithinContinent();
                    }

                    if ($foundQuest || $loopCount > 100) {
                        $search = false;
                    }
                    $loopCount++;
                }
            }
        });
    }

    protected function getInitialJoinedQuests()
    {
        $currentWeeksCampaign = $this->squad->getThisWeeksCampaign();
        if ($currentWeeksCampaign) {
            $quests = $currentWeeksCampaign->campaignStops->map(function (CampaignStop $campaignStop) {
                return $campaignStop->quest;
            });
            return new QuestCollection($quests);
        }
        return new QuestCollection();
    }

    protected function joinNewQuestAndSideQuests()
    {
        $quests = $this->squad->province->quests;

        if ($quests->isNotEmpty()) {

            /** @var Quest $questToJoin */
            $questToJoin = $quests->first(function (Quest $quest) {
                return ! $this->joinedQuests->contains($quest);
            });

            if (is_null($questToJoin)) {
                return false;
            }

            $campaignStop = $this->joinQuestAction->execute($this->squad, $questToJoin);
            $sideQuests = $questToJoin->sideQuests->shuffle();

            /*
             * Join side quests
             */
            foreach (range(1, $this->sideQuestsPerQuest) as $sideQuestCount) {
                $sideQuest = $sideQuests->shift();
                if ($sideQuest) {
                    $this->joinSideQuestAction->execute($campaignStop, $sideQuest);
                }
            }

            $this->squad = $this->squad->fresh();
            $this->joinedQuests->push($questToJoin);
            return true;
        }
        return false;
    }

    protected function borderTravelWithinContinent()
    {
        $border = $this->getBorderToTravelTo();
        $this->borderTravelAction->execute($this->squad, $border);
        $this->visitedProvinces->push($border);
        $this->squad = $this->squad->fresh();
    }

    /**
     * @return Province
     */
    protected function getBorderToTravelTo()
    {

        /*
         * We want to find a bordered province we haven't already visited AND has a quest, then
         * we fall back on the requirement of having a quest, and then again on whether we've
         * already visited it
         */
        $continentalBorders = $this->squad->province->borders()
            ->with('quests')
            ->where('continent_id', '=', $this->continentID)
            ->inRandomOrder()
            ->get();

        $notAlreadyVisited = $continentalBorders->reject(function (Province $province) {
            return in_array($province->id, $this->visitedProvinces->pluck('id')->toArray());
        });

        $unvisitedBorderWithQuest = $notAlreadyVisited->first(function (Province $province) {
            return $province->quests->isNotEmpty();
        });

        if ($unvisitedBorderWithQuest) {
            return $unvisitedBorderWithQuest;
        }

        $unvisitedBorder = $notAlreadyVisited->first();
        if ($unvisitedBorder) {
            return $unvisitedBorder;
        }

        return $continentalBorders->first();
    }
}
