<?php


namespace App\Domain\Actions;


use App\Domain\Models\Minion;
use App\Domain\Models\Quest;
use App\Domain\Models\Skirmish;
use App\Domain\Models\SkirmishBlueprint;
use Exception;
use Illuminate\Support\Str;

class CreateSkirmishAction
{
    /**
     * @param SkirmishBlueprint $blueprint
     * @param Quest $quest
     * @return Skirmish
     * @throws Exception
     */
    public function execute(SkirmishBlueprint $blueprint, Quest $quest)
    {
        $minions = $blueprint->minions;

        if ($minions->isEmpty()) {
            throw new Exception("No minions for skirmish blueprint: " . $blueprint->name);
        }

        /** @var Skirmish $skirmish */
        $skirmish = Skirmish::query()->create([
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
