<?php


namespace App\Domain\Actions\Content;


use App\Domain\Models\ChestBlueprint;
use App\Domain\Models\Minion;
use App\Domain\Models\SideQuestBlueprint;
use Illuminate\Support\Facades\DB;

class CreateSideQuestBlueprint
{
    /**
     * @param string|null $name
     * @param string $referenceID
     * @param array $minionArrays
     * @param array $chestBlueprintArrays
     * @return SideQuestBlueprint
     */
    public function execute(?string $name, string $referenceID, array $minionArrays, array $chestBlueprintArrays)
    {
        return DB::transaction(function () use ($name, $referenceID, $minionArrays, $chestBlueprintArrays) {

            if (count($minionArrays) === 0) {
                throw new \Exception("A side-quest-blueprint must have minions");
            }

            if (count($chestBlueprintArrays) === 0 ) {
                throw new \Exception("A side-quest-blueprint must have chest-blueprints");
            }

            /** @var SideQuestBlueprint $sideQuestBlueprint */
            $sideQuestBlueprint = SideQuestBlueprint::query()->create([
                'name' => $name,
                'reference_id' => $referenceID
            ]);

            foreach ($minionArrays as $minionArray) {
                $minion = Minion::query()->where('name', '=', $minionArray['name'])->first();
                if (! $minion) {
                    throw new \Exception("Couldn't find minion with name" . $minionArray['name']);
                }
                $sideQuestBlueprint->minions()->save($minion, [
                    'count' => $minionArray['count']
                ]);
            }

            foreach ($chestBlueprintArrays as $chestBlueprintArray) {
                $chestBlueprint = ChestBlueprint::query()->where('reference_id', '=', $chestBlueprintArray['reference_id'])->firstOrFail();
                $sideQuestBlueprint->chestBlueprints()->save($chestBlueprint, [
                    'count' => $chestBlueprintArray['count'],
                    'chance' => $chestBlueprintArray['chance']
                ]);
            }

            return $sideQuestBlueprint->fresh();
        });
    }
}
