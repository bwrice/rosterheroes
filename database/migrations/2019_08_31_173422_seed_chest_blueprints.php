<?php

use App\ChestBlueprint;
use App\Domain\Models\ItemBlueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedChestBlueprints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $itemBlueprints = ItemBlueprint::all();

        $chestBlueprintArrays = [
            [
                'reference_id' => ChestBlueprint::LOW_TIER_WARRIOR_CHEST,
                'grade' => 10,
                'min_gold' => 100,
                'max_gold' => 1000,
                'item_blueprints' => [
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_SWORD,
                        'chance' => 25
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_AXE,
                        'chance' => 25
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_MACE,
                        'chance' => 25
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_POLEARM,
                        'chance' => 25
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_TWO_HAND_SWORD,
                        'chance' => 25
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_TWO_HAND_AXE,
                        'chance' => 25
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_SHIELD,
                        'chance' => 50
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_HELMET,
                        'chance' => 15
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_HEAVY_ARMOR,
                        'chance' => 15
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_GAUNTLETS,
                        'chance' => 15
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_BELT,
                        'chance' => 15
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_BOOTS,
                        'chance' => 15
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_NECKLACE,
                        'chance' => 10
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_BRACELET,
                        'chance' => 10
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_RING,
                        'chance' => 10
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_CROWN,
                        'chance' => 10
                    ],
                ]
            ],
        ];

        foreach ($chestBlueprintArrays as $blueprintArray) {

            /** @var ChestBlueprint $chestBlueprint */
            $chestBlueprint = ChestBlueprint::query()->create([
                'reference_id' => $blueprintArray['reference_id'],
                'grade' => $blueprintArray['grade'],
                'min_gold' => $blueprintArray['min_gold'],
                'max_gold' => $blueprintArray['max_gold'],
            ]);

            foreach($blueprintArray['item_blueprints'] as $itemBlueprintArray) {
                $itemBlueprintToAttach = $itemBlueprints->first(function (ItemBlueprint $itemBlueprint) use ($itemBlueprintArray) {
                    return $itemBlueprint->reference_id === $itemBlueprintArray['reference_id'];
                });

                $chestBlueprint->itemBlueprints()->save($itemBlueprintToAttach, [
                    'chance' => $itemBlueprintArray['chance']
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
