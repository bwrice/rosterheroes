<?php


namespace App\Domain\Actions;


use App\ChestBlueprint;
use App\Domain\Models\Minion;
use App\Domain\Models\Quest;
use App\Domain\Models\SideQuest;
use App\Domain\Models\SideQuestBlueprint;
use Exception;
use Illuminate\Support\Str;

class CreateSideQuest
{
    /**
     * @param SideQuestBlueprint $blueprint
     * @param Quest $quest
     * @return SideQuest
     * @throws Exception
     */
    public function execute(SideQuestBlueprint $blueprint, Quest $quest)
    {
        $minions = $blueprint->minions;

        if ($minions->isEmpty()) {
            throw new Exception("No minions for SideQuestBlueprint: " . $blueprint->name);
        }

        $chestBlueprints = $blueprint->chestBlueprints;
        if ($chestBlueprints->isEmpty()) {
            throw new Exception("No chest-blueprints for SideQuestBlueprint: " . $blueprint->name);
        }

        /** @var SideQuest $sideQuest */
        $sideQuest = SideQuest::query()->create([
            'uuid' => Str::uuid(),
            'quest_id' => $quest->id,
            'side_quest_blueprint_id' => $blueprint->id,
            'name' => $blueprint->name,
        ]);

        $minions->each(function (Minion $minion) use ($sideQuest) {
            $sideQuest->minions()->save($minion, [
                'count' => $minion->pivot->count
            ]);
        });

        $chestBlueprints->each(function (ChestBlueprint $chestBlueprint) use ($sideQuest) {
            $sideQuest->chestBlueprints()->save($chestBlueprint, [
                'count' => $chestBlueprint->pivot->count
            ]);
        });

        return $sideQuest;
    }
}
