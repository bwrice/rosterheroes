<?php


namespace App\Domain\Actions;


use App\Domain\Models\Minion;
use App\Domain\Models\Quest;
use App\Domain\Models\SideQuest;
use App\Domain\Models\SideQuestBlueprint;
use Exception;
use Illuminate\Support\Str;

class CreateSideQuestAction
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
            throw new Exception("No minions for skirmish blueprint: " . $blueprint->name);
        }

        /** @var SideQuest $skirmish */
        $skirmish = SideQuest::query()->create([
            'uuid' => Str::uuid(),
            'quest_id' => $quest->id,
            'skirmish_blueprint_id' => $blueprint->id,
            'name' => $blueprint->name,
        ]);

        $minions->each(function (Minion $minion) use ($skirmish) {
            $skirmish->minions()->save($minion, [
                'count' => $minion->pivot->count
            ]);
        });

        return $skirmish;
    }
}
