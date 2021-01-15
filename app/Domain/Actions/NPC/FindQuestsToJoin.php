<?php


namespace App\Domain\Actions\NPC;


use App\Domain\Models\Continent;
use App\Domain\Models\Quest;
use App\Domain\Models\SideQuest;
use App\Domain\Models\Squad;
use App\Facades\CurrentWeek;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class FindQuestsToJoin
{
    /**
     * @param Squad $npc
     * @return Collection
     */
    public function execute(Squad $npc)
    {
        if (CurrentWeek::adventuringLocked()) {
            return collect();
        }

        $availableCampaignStops = $this->getAvailableCampaignStops($npc);

        if (! $availableCampaignStops > 0) {
            return collect();
        }

        $npcLevel = $npc->level();
        $idealDifficulty = ($npcLevel * 2) + 5;
        $questsToJoin = $this->getQuestsToJoin($npcLevel, $idealDifficulty, $availableCampaignStops);

        $sideQuestsPerQuest = $npc->getSideQuestsPerQuest();

        return $questsToJoin->map(function (Quest $quest) use ($sideQuestsPerQuest, $idealDifficulty) {

            $sideQuests = $quest->sideQuests->sortBy(function (SideQuest $sideQuest) use ($idealDifficulty) {
                return abs($idealDifficulty - $sideQuest->difficulty());
            });

            $sideQuestsCount = $sideQuests->count();

            // Get a rand int between side-quests-per-quest and total side-quests available
            $amountOfQuestsToTake = $sideQuestsCount <= $sideQuestsPerQuest ? $sideQuestsCount : rand($sideQuestsPerQuest, $sideQuestsCount);
            $sideQuestsToJoin = $sideQuests->take($amountOfQuestsToTake)->random($sideQuestsPerQuest);

            return [
                'quest' => $quest,
                'side_quests' =>  $sideQuestsToJoin->values()->all()
            ];
        });
    }

    /**
     * @param Squad $npc
     * @return int
     */
    protected function getAvailableCampaignStops(Squad $npc): int
    {
        $questsPerWeek = $npc->getQuestsPerWeek();
        $currentCampaign = $npc->getCurrentCampaign();
        if ($currentCampaign) {
            $availableCampaignStops = $questsPerWeek - $currentCampaign->campaignStops()->count();
        } else {
            $availableCampaignStops = $questsPerWeek;
        }
        return $availableCampaignStops;
    }

    /**
     * @param int $npcLevel
     * @param $idealDifficulty
     * @param int $availableCampaignStops
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection|mixed
     */
    protected function getQuestsToJoin(int $npcLevel, $idealDifficulty, int $availableCampaignStops)
    {
        // Get quests located in province npc can visit
        $validContinentIDs = Continent::query()->get()->filter(function (Continent $continent) use ($npcLevel) {
            return $continent->getBehavior()->getMinLevelRequirement() <= $npcLevel;
        })->pluck('id')->toArray();

        // If there are over 50 quests, we can safely grab 50 randomly and find some ideal quests to join
        $quests = Quest::query()->with('sideQuests.minions')->whereHas('province', function (Builder $builder) use ($validContinentIDs) {
            $builder->whereIn('continent_id', $validContinentIDs);
        })->inRandomOrder()->limit(50)->get();


        // Sort quests by average difficulty of side-quest relative to ideal difficulty for npc
        $orderedQuests = $quests->sortBy(function (Quest $quest) use ($npcLevel, $idealDifficulty) {
            $sideQuestDifficultyAvg = $quest->sideQuests->average(function (SideQuest $sideQuest) {
                return $sideQuest->difficulty();
            });
            return abs($idealDifficulty - $sideQuestDifficultyAvg);
        });

        $questsCount = $orderedQuests->count();

        /*
         * Get a rand int between quests-per-week and total quests available
         * This will make ideal-difficulty quests more likely to be chosen
         */
        $amountOfQuestsToTake = $questsCount <= $availableCampaignStops ? $questsCount : rand($availableCampaignStops, $questsCount);
        $questsToJoin = $orderedQuests->take($amountOfQuestsToTake)->random($availableCampaignStops);
        return $questsToJoin;
    }
}
