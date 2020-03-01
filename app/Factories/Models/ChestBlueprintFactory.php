<?php


namespace App\Factories\Models;


use App\ChestBlueprint;
use Illuminate\Support\Collection;

class ChestBlueprintFactory
{
    /** @var Collection|null */
    protected $itemBlueprintFactories;

    public static function new(): self
    {
        return new self();
    }

    public function create(array $extra = [])
    {
        $grade = rand(1, 100);
        $minGold = rand(50, 5000);
        $maxGold = rand($minGold, $minGold * 10);

        /** @var ChestBlueprint $chestBlueprint */
        $chestBlueprint = ChestBlueprint::query()->create(array_merge([
            'grade' => $grade,
            'min_gold' => $minGold,
            'max_gold' => $maxGold
        ], $extra));

        if ($this->itemBlueprintFactories) {
            $this->itemBlueprintFactories->each(function (ItemBlueprintFactory $itemBlueprintFactory) use ($chestBlueprint) {
                $itemBlueprint = $itemBlueprintFactory->create();
                $chestBlueprint->itemBlueprints()->save($itemBlueprint, [
                    'chance' => $itemBlueprintFactory->getChestBlueprintChance() ?: rand(1, 100)
                ]);
            });
        }

        return $chestBlueprint;
    }

    public function withItemBlueprints(Collection $itemBlueprintFactories = null)
    {
        if (! $itemBlueprintFactories) {
            $itemBlueprintFactories = collect();
            foreach (range(1, rand(1, 4)) as $count) {
                $itemBlueprintFactories->push(ItemBlueprintFactory::new());
            }
        }
        $clone = clone $this;
        $clone->itemBlueprintFactories = $itemBlueprintFactories;
        return $clone;
    }
}
