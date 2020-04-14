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
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_ITEM,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_SWORD,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_AXE,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MACE,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_POLEARM,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_TWO_HAND_SWORD,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_TWO_HAND_AXE,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_SHIELD,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HELMET,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HEAVY_ARMOR,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_GAUNTLETS,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_BELT,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_BOOTS,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_ITEM,
                        'chance' => 10
                    ],
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
            [
                'reference_id' => ChestBlueprint::LOW_TIER_RANGER_CHEST,
                'grade' => 10,
                'min_gold' => 100,
                'max_gold' => 1000,
                'item_blueprints' => [
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_ITEM,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_DAGGER,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_BOW,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_CROSSBOW,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_THROWING_WEAPON,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LIGHT_ARMOR,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HELMET,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_GAUNTLETS,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_BELT,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LEGGINGS,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_ITEM,
                        'chance' => 10
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_DAGGER,
                        'chance' => 25
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_BOW,
                        'chance' => 50
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_CROSSBOW,
                        'chance' => 50
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_THROWING_WEAPON,
                        'chance' => 25
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_LIGHT_ARMOR,
                        'chance' => 20
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_HELMET,
                        'chance' => 20
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_GAUNTLETS,
                        'chance' => 20
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_BELT,
                        'chance' => 20
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_LEGGINGS,
                        'chance' => 20
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
            [
                'reference_id' => ChestBlueprint::LOW_TIER_SORCERER_CHEST,
                'grade' => 10,
                'min_gold' => 100,
                'max_gold' => 1000,
                'item_blueprints' => [
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_ITEM,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_WAND,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_ORB,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_STAFF,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_PSIONIC_ONE_HAND,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_PSIONIC_TWO_HAND,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_PSIONIC_SHIELD,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_CAP,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_ROBES,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_GLOVES,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_SHOES,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_SASH,
                        'chance' => 2.5
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_ITEM,
                        'chance' => 10
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_WAND,
                        'chance' => 35
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_ORB,
                        'chance' => 35
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_STAFF,
                        'chance' => 35
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_PSIONIC_ONE_HAND,
                        'chance' => 15
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_PSIONIC_TWO_HAND,
                        'chance' => 15
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_PSIONIC_SHIELD,
                        'chance' => 15
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_CAP,
                        'chance' => 20
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_ROBES,
                        'chance' => 20
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_GLOVES,
                        'chance' => 20
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_SHOES,
                        'chance' => 20
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_SASH,
                        'chance' => 20
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_BRACELET,
                        'chance' => 15
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_RING,
                        'chance' => 15
                    ],
                    [
                        'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_CROWN,
                        'chance' => 15
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
