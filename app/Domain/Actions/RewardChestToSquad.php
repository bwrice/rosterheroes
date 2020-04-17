<?php


namespace App\Domain\Actions;


use App\Aggregates\ChestAggregate;
use App\Chest;
use App\ChestBlueprint;
use App\Domain\Interfaces\RewardsChests;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\Squad;
use Illuminate\Support\Str;

class RewardChestToSquad
{
    /**
     * @var GenerateItemFromBlueprintAction
     */
    protected $generateItem;

    public function __construct(GenerateItemFromBlueprintAction $generateItem)
    {
        $this->generateItem = $generateItem;
    }

    /**
     * @param ChestBlueprint $chestBlueprint
     * @param Squad $squad
     * @param RewardsChests|null $source
     * @return Chest
     */
    public function execute(ChestBlueprint $chestBlueprint, Squad $squad, ?RewardsChests $source)
    {
        $uuid = (string) Str::uuid();
        $chestAggregate = ChestAggregate::retrieve($uuid);
        $chestGold = rand($chestBlueprint->min_gold, $chestBlueprint->max_gold);

        $sourceType = $source ? $source->getMorphType() : null;
        $sourceID = $source ? $source->getMorphID() : null;

        $chestAggregate->createNewChest(
            $chestBlueprint->quality,
            $chestBlueprint->size,
            $chestGold,
            $squad->id,
            $chestBlueprint->id,
            $sourceType,
            $sourceID
        );
        $chestAggregate->persist();

        $chest = Chest::findUuidOrFail($uuid);

        $itemBlueprints = $chestBlueprint->itemBlueprints;
        $itemBlueprints->each(function (ItemBlueprint $itemBlueprint) use ($chest) {

            $count = $itemBlueprint->pivot->count;
            $chance = $itemBlueprint->pivot->chance;

            for ($i = 1; $i <= $count; $i++) {
                $randomChancePercent = (rand(0, 10000)/100);
                // We add PHP_FLOAT_EPSILON to guarantee 100% chance item-blueprints always get rewarded
                $rewardItem = $randomChancePercent <= ($chance + PHP_FLOAT_EPSILON);
                if ($rewardItem) {
                    $item = $this->generateItem->execute($itemBlueprint);
                    $chest->items()->save($item);
                }
            }
        });

        return $chest->fresh();
    }
}
