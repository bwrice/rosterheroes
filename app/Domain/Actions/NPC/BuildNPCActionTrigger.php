<?php


namespace App\Domain\Actions\NPC;


use App\Domain\Actions\NPC\ActionTriggers\NPCActionTrigger;
use App\Domain\Models\Continent;
use App\Domain\Models\Quest;
use App\Domain\Models\SideQuest;
use App\Domain\Models\Squad;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class BuildNPCActionTrigger
 * @package App\Domain\Actions\NPC
 *
 * @method NPCActionTrigger execute(Squad $squad, float $baseTriggerChance)
 */
class BuildNPCActionTrigger extends NPCAction
{
    /**
     * @param float $baseTriggerChance
     * @return NPCActionTrigger
     */
    public function handleExecute(float $baseTriggerChance)
    {
        $actionTrigger = new NPCActionTrigger($baseTriggerChance);
        $actionTrigger = $this->updateTriggerForOpeningChests($actionTrigger);
        $actionTrigger = $this->updateTriggerForJoiningQuests($actionTrigger);
        return $actionTrigger;
    }

    /**
     * @param NPCActionTrigger $npcActionTrigger
     * @return NPCActionTrigger
     */
    protected function updateTriggerForOpeningChests(NPCActionTrigger $npcActionTrigger)
    {
        $unopenedChests = $this->npc->chests()
            ->where('opened_at', '=', null)
            ->get();

        if ($unopenedChests->isEmpty()) {
            return $npcActionTrigger;
        }
        $trigger = (rand(1, 100) <= $npcActionTrigger->getTriggerChance());

        if (! $trigger) {
            return $npcActionTrigger;
        }

        $capacityCount = (int) ceil($this->npc->getAvailableCapacity()/50);
        $chestsCount = (int) min($unopenedChests->count(), $capacityCount);

        return $npcActionTrigger->pushAction(NPCActionTrigger::KEY_OPEN_CHESTS, $unopenedChests->take($chestsCount));
    }

    protected function updateTriggerForJoiningQuests(NPCActionTrigger $actionTrigger)
    {
        $npcLevel = $this->npc->level();

        // Get quests located in province npc can visit
        $validContinentIDs = Continent::query()->get()->filter(function (Continent $continent) use ($npcLevel) {
            return $continent->getBehavior()->getMinLevelRequirement() <= $npcLevel;
        })->pluck('id')->toArray();

        // If there are over 50 quests, we can safely grab 50 randomly and find some ideal quests to join
        $quests = Quest::query()->with('sideQuests.minions')->whereHas('province', function (Builder $builder) use ($validContinentIDs) {
            $builder->whereIn('continent_id', $validContinentIDs);
        })->inRandomOrder()->limit(50)->get();

        $idealDifficulty = ($npcLevel * 2) + 5;

        // Sort quests by average difficulty of side-quest relative to ideal difficulty for npc
        $orderedQuests = $quests->sortBy(function (Quest $quest) use ($npcLevel, $idealDifficulty) {
            $sideQuestDifficultyAvg = $quest->sideQuests->average(function (SideQuest $sideQuest) {
                return $sideQuest->difficulty();
            });
            return abs($idealDifficulty - $sideQuestDifficultyAvg);
        });

        $questsCount = $orderedQuests->count();
        $questsPerWeek = $this->npc->getQuestsPerWeek();

        /*
         * Get a rand int between quests-per-week and total quests available
         * This will make ideal-difficulty quests more likely to be chosen
         */
        $amountOfQuestsToTake = $questsCount <= $questsPerWeek ? $questsCount : rand($questsPerWeek, $questsCount);
        $questsToJoin = $orderedQuests->take($amountOfQuestsToTake)->random($questsPerWeek);

        $sideQuestsPerQuest = $this->npc->getSideQuestsPerQuest();

        $data = $questsToJoin->map(function (Quest $quest) use ($sideQuestsPerQuest, $idealDifficulty) {

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

        $actionTrigger->pushAction(NPCActionTrigger::KEY_JOIN_QUESTS, $data);
        return $actionTrigger;
    }
}
