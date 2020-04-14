<?php


namespace App\Domain\Actions;


use App\Aggregates\ChestAggregate;
use App\Chest;
use App\ChestBlueprint;
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
     * @return Chest
     */
    public function execute(ChestBlueprint $chestBlueprint, Squad $squad)
    {
        $uuid = (string) Str::uuid();
        $chestAggregate = ChestAggregate::retrieve($uuid);
        $chestGold = rand($chestBlueprint->min_gold, $chestBlueprint->max_gold);
        $chestAggregate->createNewChest($chestBlueprint->quality_tier, $chestBlueprint->size_tier, $chestGold, $squad->id, $chestBlueprint->id);
        $chestAggregate->persist();

        $chest = Chest::findUuidOrFail($uuid);

        $itemBlueprints = $chestBlueprint->itemBlueprints;
        $itemBlueprints->each(function (ItemBlueprint $itemBlueprint) use ($chest) {
            $chance = $itemBlueprint->pivot->chance;
            $randomChancePercent = (rand(0, 10000)/100);
            $rewardItem = $randomChancePercent <= $chance;
            if ($rewardItem) {
                $item = $this->generateItem->execute($itemBlueprint);
                $chest->items()->save($item);
            }
        });

        return $chest->fresh();
    }
}
