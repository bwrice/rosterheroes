<?php


namespace App\Domain\Actions\Content;


use App\Domain\Models\Attack;
use App\Domain\Models\ChestBlueprint;
use App\Domain\Models\CombatPosition;
use App\Domain\Models\EnemyType;
use App\Domain\Models\Minion;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class CreateMinion
{
    /**
     * @param string $name
     * @param int $level
     * @param string $enemyTypeName
     * @param string $combatPositionName
     * @param array $attackNames
     * @param array $chestBlueprintSeedArrays
     * @throws \Exception
     * @return Minion
     */
    public function execute(
        string $name,
        int $level,
        string $enemyTypeName,
        string $combatPositionName,
        array $attackNames,
        array $chestBlueprintSeedArrays
    )
    {
        $attacks = $this->getAttacks($attackNames);
        $chestBlueprintSeeds = $this->getChestBlueprintSeeds($chestBlueprintSeedArrays);

        $enemyType = EnemyType::query()->where('name', '=', $enemyTypeName)->first();
        $combatPosition = CombatPosition::query()->where('name', '=', $combatPositionName)->first();

        /** @var Minion $minion */
        $minion = Minion::query()->create([
            'uuid' => Str::uuid(),
            'name' => $name,
            'level' => $level,
            'enemy_type_id' => $enemyType->id,
            'combat_position_id' => $combatPosition->id
        ]);

        $minion->attacks()->saveMany($attacks);

        $chestBlueprintSeeds->each(function ($chestBlueprintSeed) use ($minion) {
            $minion->chestBlueprints()->save($chestBlueprintSeed['chest_blueprint'], [
                'count' => $chestBlueprintSeed['count'],
                'chance' => $chestBlueprintSeed['chance']
            ]);
        });

        return $minion;
    }

    /**
     * @param array $attackNames
     * @return Collection
     * @throws \Exception
     */
    protected function getAttacks(array $attackNames)
    {
        $attackNames = collect($attackNames);
        if ($attackNames->isEmpty()) {
            throw new \Exception("A minion requires attacks");
        }

        $attacks = Attack::query()->whereIn('name', $attackNames)->get();
        if ($attackNames->count() !== $attacks->count()) {
            $missing = $attackNames->first(function ($attackName) use ($attacks) {
                $match = $attacks->first(function (Attack $attack) use ($attackName) {
                    return $attack->name === $attackName;
                });
                return is_null($match);
            });
            throw new \Exception($missing . " attack not found");
        }
        return $attacks;
    }

    /**
     * @param array $chestBlueprintSeedArrays
     * @throws \Exception
     * @return Collection
     */
    protected function getChestBlueprintSeeds(array $chestBlueprintSeedArrays)
    {
        $chestBlueprintSeedArrays = collect($chestBlueprintSeedArrays);

        if ($chestBlueprintSeedArrays->isEmpty()) {
            throw new \Exception("Minion must have some chest possibilities");
        }

        $referenceIDs = $chestBlueprintSeedArrays->map(function ($blueprintArray) {
            return $blueprintArray['chest_blueprint'];
        });

        $chestBlueprints = ChestBlueprint::query()->whereIn('reference_id', $referenceIDs->toArray())->get();

        if ($chestBlueprints->count() !== $referenceIDs->count()) {
            $missing = $referenceIDs->first(function ($refID) use ($chestBlueprints) {
                $match = $chestBlueprints->first(function (ChestBlueprint $chestBlueprint) use ($refID) {
                    return $chestBlueprint->reference_id === $refID;
                });
                return is_null($match);
            });
            throw new \Exception("ChestBlueprint with reference ID: " . $missing . " not found");
        }

        return $chestBlueprintSeedArrays->map(function ($seedArray) use ($chestBlueprints) {
            $matchingBlueprint = $chestBlueprints->first(function (ChestBlueprint $chestBlueprint) use ($seedArray) {
                return $chestBlueprint->reference_id === $seedArray['chest_blueprint'];
            });
            $seedArray['chest_blueprint'] = $matchingBlueprint;
            return $seedArray;
        });
    }
}
