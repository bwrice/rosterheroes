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
            $this->getChestBlueprintArray(ChestBlueprint::LOW_TIER_SMALL_WARRIOR_CHEST, 2, 1, function ($size) {
                return $this->getLowTierWarriorItemBlueprintArrays($size);
            }),
            $this->getChestBlueprintArray(ChestBlueprint::LOW_TIER_SMALL_RANGER_CHEST, 2, 1, function ($size) {
                return $this->getLowTierRangerItemBlueprintArrays($size);
            }),
            $this->getChestBlueprintArray(ChestBlueprint::LOW_TIER_SMALL_SORCERER_CHEST, 2, 1, function ($size) {
                return $this->getLowTierSorcererBlueprintArrays($size);
            }),
        ];

        foreach ($chestBlueprintArrays as $blueprintArray) {

            /** @var ChestBlueprint $chestBlueprint */
            $chestBlueprint = ChestBlueprint::query()->create([
                'reference_id' => $blueprintArray['reference_id'],
                'quality' => $blueprintArray['quality'],
                'size' => $blueprintArray['size'],
                'min_gold' => $blueprintArray['min_gold'],
                'max_gold' => $blueprintArray['max_gold'],
            ]);

            foreach($blueprintArray['item_blueprints'] as $itemBlueprintArray) {
                $itemBlueprintToAttach = $itemBlueprints->first(function (ItemBlueprint $itemBlueprint) use ($itemBlueprintArray) {
                    return $itemBlueprint->reference_id === $itemBlueprintArray['reference_id'];
                });

                $chestBlueprint->itemBlueprints()->save($itemBlueprintToAttach, [
                    'chance' => $itemBlueprintArray['chance'],
                    'count' => $itemBlueprintArray['count']
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

    /**
     * @param string $referenceID
     * @param int $size
     * @param int $quality
     * @param callable $getItemBlueprintArrays
     * @return array
     */
    protected function getChestBlueprintArray(string $referenceID, int $size, int $quality, callable $getItemBlueprintArrays): array
    {
        return [
            'reference_id' => $referenceID,
            'quality' => 1,
            'size' => $size,
            'min_gold' => $minGold = (int) ceil(25 * ($size**2) * ($quality**2)),
            'max_gold' => 5 * $size * $quality * $minGold,
            'item_blueprints' => $getItemBlueprintArrays($size)
        ];
    }

    /**
     * @param int $size
     * @return array
     */
    protected function getLowTierWarriorItemBlueprintArrays(int $size): array
    {
        return [
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_ITEM,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_SWORD,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_AXE,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MACE,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_POLEARM,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_TWO_HAND_SWORD,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_TWO_HAND_AXE,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_SHIELD,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HELMET,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HEAVY_ARMOR,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_GAUNTLETS,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_BELT,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_BOOTS,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_ITEM,
                'chance' => 10,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_SWORD,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_AXE,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_MACE,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_POLEARM,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_TWO_HAND_SWORD,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_TWO_HAND_AXE,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_SHIELD,
                'chance' => 35,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_HELMET,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_HEAVY_ARMOR,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_GAUNTLETS,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_BELT,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_BOOTS,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_NECKLACE,
                'chance' => 10,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_BRACELET,
                'chance' => 10,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_RING,
                'chance' => 10,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_CROWN,
                'chance' => 10,
                'count' => $size - 1
            ],
        ];
    }

    /**
     * @param $size
     * @return array
     */
    protected function getLowTierRangerItemBlueprintArrays($size): array
    {
        return [
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_ITEM,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_DAGGER,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_BOW,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_CROSSBOW,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_THROWING_WEAPON,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LIGHT_ARMOR,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HELMET,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_GAUNTLETS,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_BELT,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LEGGINGS,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_ITEM,
                'chance' => 10,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_DAGGER,
                'chance' => 25,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_BOW,
                'chance' => 40,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_CROSSBOW,
                'chance' => 40,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_THROWING_WEAPON,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_LIGHT_ARMOR,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_HELMET,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_GAUNTLETS,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_BELT,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_LEGGINGS,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_NECKLACE,
                'chance' => 10,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_BRACELET,
                'chance' => 10,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_RING,
                'chance' => 10,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_CROWN,
                'chance' => 10,
                'count' => $size - 1
            ],
        ];
    }

    /**
     * @param $size
     * @return array
     */
    protected function getLowTierSorcererBlueprintArrays($size): array
    {
        return [
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_ITEM,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_WAND,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_ORB,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_STAFF,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_PSIONIC_ONE_HAND,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_PSIONIC_TWO_HAND,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_PSIONIC_SHIELD,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_CAP,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_ROBES,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_GLOVES,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_SHOES,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_SASH,
                'chance' => 2.5,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_ITEM,
                'chance' => 10,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_WAND,
                'chance' => 30,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_ORB,
                'chance' => 30,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_STAFF,
                'chance' => 30,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_PSIONIC_ONE_HAND,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_PSIONIC_TWO_HAND,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_PSIONIC_SHIELD,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_CAP,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_ROBES,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_GLOVES,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_SHOES,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_SASH,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_BRACELET,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_RING,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_LOW_TIER_CROWN,
                'chance' => 15,
                'count' => $size - 1
            ],
        ];
    }
}
