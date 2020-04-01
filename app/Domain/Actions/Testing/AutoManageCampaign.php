<?php


namespace App\Domain\Actions\Testing;


use App\Aggregates\CampaignAggregate;
use App\Domain\Actions\JoinQuestAction;
use App\Domain\Actions\JoinSideQuestAction;
use App\Domain\Actions\SquadBorderTravelAction;
use App\Domain\Collections\ProvinceCollection;
use App\Domain\Collections\QuestCollection;
use App\Domain\Models\Continent;
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
            foreach (range (1, $this->questsPerWeek) as $count) {

                if ($count > 1) {
                    $search = true;
                    $count = 1;

                    while($search) {

                        $foundQuest = $this->joinNewQuestAndSideQuests();

                        /*
                         * Border travel if we didn't just join our last needed quest
                         */
                        if ($this->questsPerWeek != $count && !$foundQuest) {
                            $this->borderTravelWithinContinent();
                        }

                        if ($foundQuest || $count > 9) {
                            $search = false;
                        }
                        $count++;
                    }
                }
            }
        });
    }

    protected function getInitialJoinedQuests()
    {
        $currentWeeksCampaign = $this->squad->getThisWeeksCampaign();
        if ($currentWeeksCampaign) {
            return $currentWeeksCampaign->quests;
        }
        return new QuestCollection();
    }

    protected function joinNewQuestAndSideQuests()
    {
        $quests = $this->squad->province->quests;

        if ($quests->isNotEmpty()) {

            $questToJoin = $quests->first(function (Quest $quest) {
                return ! $this->joinedQuests->contains($quest);
            });

            if (is_null($questToJoin)) {
                return false;
            }

            $this->joinQuestAction->execute($this->squad, $questToJoin);
            // TODO: sidequests
            $this->squad = $this->squad->fresh();
            $this->joinedQuests->push($questToJoin);
        }
        return false;
    }

    protected function borderTravelWithinContinent()
    {
        $border = $this->getBorderToTravelTo();
        $this->borderTravelAction->execute($this->squad, $border);
        $this->squad = $this->squad->fresh();
    }

    /**
     * @return Province
     */
    protected function getBorderToTravelTo()
    {
        /*
         * We want to find a bordered province we haven't already visited but that won't always be possible,
         * so we fall back to any border within the continent
         */
        $continentalBorders = $this->squad->province->borders()->where('continent_id', '=', $this->continentID)->get();
        $border = $continentalBorders->shuffle()->first(function (Province $province) {
            return ! in_array($province->id, $this->visitedProvinces->pluck('id')->toArray());
        });

        if (! $border) {
            $border = $continentalBorders->shuffle()->first();
        }
        return $border;
    }
}
