<?php


namespace App\Factories\Models;


use App\Domain\Models\ChestBlueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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
        $qualityTier = rand(1, 6);
        $sizeTier = rand(1, 6);
        $minGold = rand(50, 5000);
        $maxGold = rand($minGold, $minGold * 10);

        /** @var ChestBlueprint $chestBlueprint */
        $chestBlueprint = ChestBlueprint::query()->create(array_merge([
            'uuid' => (string) Str::uuid(),
            'quality' => $qualityTier,
            'size' => $sizeTier,
            'min_gold' => $minGold,
            'max_gold' => $maxGold,
            'description' => 'Test Chest Blueprint ' . Str::random(8)
        ], $extra));

        if ($this->itemBlueprintFactories) {
            $this->itemBlueprintFactories->each(function (ItemBlueprintFactory $itemBlueprintFactory) use ($chestBlueprint) {
                $itemBlueprint = $itemBlueprintFactory->create();
                $chance = is_null($itemBlueprintFactory->getChestBlueprintChance()) ? rand(1, 100) : $itemBlueprintFactory->getChestBlueprintChance();
                $count = $itemBlueprintFactory->getChestBlueprintCount() ?: 1;
                $chestBlueprint->itemBlueprints()->save($itemBlueprint, [
                    'chance' => $chance,
                    'count' => $count
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
