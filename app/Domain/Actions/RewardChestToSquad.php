<?php


namespace App\Domain\Actions;

use App\Domain\Models\Chest;
use App\Domain\Models\ChestBlueprint;
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
        $chestGold = rand($chestBlueprint->min_gold, $chestBlueprint->max_gold);

        $sourceType = $source ? $source->getMorphType() : null;
        $sourceID = $source ? $source->getMorphID() : null;

        /** @var Chest $chest */
        $chest = Chest::query()->create([
            'uuid' => (string) Str::uuid(),
            'squad_id' => $squad->id,
            'source_type' => $sourceType,
            'source_id' => $sourceID,
            'chest_blueprint_id' => $chestBlueprint->id,
            'description' => $chestBlueprint->description,
            'gold' => $chestGold,
            'quality' => $chestBlueprint->quality,
            'size' => $chestBlueprint->size
        ]);

        $itemBlueprints = $chestBlueprint->itemBlueprints;
        $itemBlueprints->each(function (ItemBlueprint $itemBlueprint) use ($chest) {

            $count = $itemBlueprint->pivot->count;
            $percentChanceTimes100 = $itemBlueprint->pivot->chance * 100;

            for ($i = 1; $i <= $count; $i++) {
                $rewardItem = (rand(1, 10000) <= $percentChanceTimes100);
                if ($rewardItem) {
                    $item = $this->generateItem->execute($itemBlueprint);
                    $chest->items()->save($item);
                }
            }
        });

        return $chest->fresh();
    }
}
