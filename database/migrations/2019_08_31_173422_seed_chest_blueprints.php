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

        $chestBlueprintArrays = collect([
            $this->getChestBlueprintArray(ChestBlueprint::LOW_TIER_SMALL_WARRIOR_CHEST, 2, 1, function ($size) {
                return $this->getLowTierWarriorItemBlueprintArrays($size);
            }),
            $this->getChestBlueprintArray(ChestBlueprint::LOW_TIER_SMALL_RANGER_CHEST, 2, 1, function ($size) {
                return $this->getLowTierRangerItemBlueprintArrays($size);
            }),
            $this->getChestBlueprintArray(ChestBlueprint::LOW_TIER_SMALL_SORCERER_CHEST, 2, 1, function ($size) {
                return $this->getLowTierSorcererBlueprintArrays($size);
            }),
            $this->getChestBlueprintArray(ChestBlueprint::LOW_TIER_MEDIUM_WARRIOR_CHEST, 3, 1, function ($size) {
                return $this->getLowTierWarriorItemBlueprintArrays($size);
            }),
            $this->getChestBlueprintArray(ChestBlueprint::LOW_TIER_MEDIUM_RANGER_CHEST, 3, 1, function ($size) {
                return $this->getLowTierRangerItemBlueprintArrays($size);
            }),
            $this->getChestBlueprintArray(ChestBlueprint::LOW_TIER_MEDIUM_SORCERER_CHEST, 3, 1, function ($size) {
                return $this->getLowTierSorcererBlueprintArrays($size);
            }),
            $this->getChestBlueprintArray(ChestBlueprint::LOW_TIER_LARGE_WARRIOR_CHEST, 4, 1, function ($size) {
                return $this->getLowTierWarriorItemBlueprintArrays($size);
            }),
            $this->getChestBlueprintArray(ChestBlueprint::LOW_TIER_LARGE_RANGER_CHEST, 4, 1, function ($size) {
                return $this->getLowTierRangerItemBlueprintArrays($size);
            }),
            $this->getChestBlueprintArray(ChestBlueprint::LOW_TIER_LARGE_SORCERER_CHEST, 4, 1, function ($size) {
                return $this->getLowTierSorcererBlueprintArrays($size);
            }),
            $this->getChestBlueprintArray(ChestBlueprint::MID_TIER_SMALL_WARRIOR_CHEST, 2, 2, function ($size) {
                return $this->getMidTierWarriorItemBlueprintArrays($size);
            }),
            $this->getChestBlueprintArray(ChestBlueprint::MID_TIER_SMALL_RANGER_CHEST, 2, 2, function ($size) {
                return $this->getMidTierRangerItemBlueprintArrays($size);
            }),
            $this->getChestBlueprintArray(ChestBlueprint::MID_TIER_SMALL_SORCERER_CHEST, 2, 2, function ($size) {
                return $this->getMidTierSorcererBlueprintArrays($size);
            }),
            $this->getChestBlueprintArray(ChestBlueprint::MID_TIER_MEDIUM_WARRIOR_CHEST, 3, 2, function ($size) {
                return $this->getMidTierWarriorItemBlueprintArrays($size);
            }),
            $this->getChestBlueprintArray(ChestBlueprint::MID_TIER_MEDIUM_RANGER_CHEST, 3, 2, function ($size) {
                return $this->getMidTierRangerItemBlueprintArrays($size);
            }),
            $this->getChestBlueprintArray(ChestBlueprint::MID_TIER_MEDIUM_SORCERER_CHEST, 3, 2, function ($size) {
                return $this->getMidTierSorcererBlueprintArrays($size);
            }),
            $this->getChestBlueprintArray(ChestBlueprint::MID_TIER_LARGE_WARRIOR_CHEST, 4, 2, function ($size) {
                return $this->getMidTierWarriorItemBlueprintArrays($size);
            }),
            $this->getChestBlueprintArray(ChestBlueprint::MID_TIER_LARGE_RANGER_CHEST, 4, 2, function ($size) {
                return $this->getMidTierRangerItemBlueprintArrays($size);
            }),
            $this->getChestBlueprintArray(ChestBlueprint::MID_TIER_LARGE_SORCERER_CHEST, 4, 2, function ($size) {
                return $this->getMidTierSorcererBlueprintArrays($size);
            }),
            $this->getChestBlueprintArray(ChestBlueprint::HIGH_TIER_SMALL_WARRIOR_CHEST, 2, 3, function ($size) {
                return $this->getHighTierWarriorItemBlueprintArrays($size);
            }),
            $this->getChestBlueprintArray(ChestBlueprint::HIGH_TIER_SMALL_RANGER_CHEST, 2, 3, function ($size) {
                return $this->getHighTierRangerItemBlueprintArrays($size);
            }),
            $this->getChestBlueprintArray(ChestBlueprint::HIGH_TIER_SMALL_SORCERER_CHEST, 2, 3, function ($size) {
                return $this->getHighTierSorcererBlueprintArrays($size);
            }),
            $this->getChestBlueprintArray(ChestBlueprint::HIGH_TIER_MEDIUM_WARRIOR_CHEST, 3, 3, function ($size) {
                return $this->getHighTierWarriorItemBlueprintArrays($size);
            }),
            $this->getChestBlueprintArray(ChestBlueprint::HIGH_TIER_MEDIUM_RANGER_CHEST, 3, 3, function ($size) {
                return $this->getHighTierRangerItemBlueprintArrays($size);
            }),
            $this->getChestBlueprintArray(ChestBlueprint::HIGH_TIER_MEDIUM_SORCERER_CHEST, 3, 3, function ($size) {
                return $this->getHighTierSorcererBlueprintArrays($size);
            }),
            $this->getChestBlueprintArray(ChestBlueprint::HIGH_TIER_LARGE_WARRIOR_CHEST, 4, 3, function ($size) {
                return $this->getHighTierWarriorItemBlueprintArrays($size);
            }),
            $this->getChestBlueprintArray(ChestBlueprint::HIGH_TIER_LARGE_RANGER_CHEST, 4, 3, function ($size) {
                return $this->getHighTierRangerItemBlueprintArrays($size);
            }),
            $this->getChestBlueprintArray(ChestBlueprint::HIGH_TIER_LARGE_SORCERER_CHEST, 4, 3, function ($size) {
                return $this->getHighTierSorcererBlueprintArrays($size);
            }),
        ])->values();

        $goldOnlyBlueprintArrays = $this->getGoldOnlyBlueprintArrays();
        $chestBlueprintArrays = $chestBlueprintArrays->merge($goldOnlyBlueprintArrays);

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

    protected function getGoldOnlyBlueprintArrays()
    {
        return collect([
            1 => ChestBlueprint::GOLD_ONLY_LEVEL_1,
            2 => ChestBlueprint::GOLD_ONLY_LEVEL_2,
            3 => ChestBlueprint::GOLD_ONLY_LEVEL_3,
            4 => ChestBlueprint::GOLD_ONLY_LEVEL_4,
            5 => ChestBlueprint::GOLD_ONLY_LEVEL_5,
            6 => ChestBlueprint::GOLD_ONLY_LEVEL_6,
            7 => ChestBlueprint::GOLD_ONLY_LEVEL_7,
            8 => ChestBlueprint::GOLD_ONLY_LEVEL_8,
            9 => ChestBlueprint::GOLD_ONLY_LEVEL_9,
            10 => ChestBlueprint::GOLD_ONLY_LEVEL_10,
            11 => ChestBlueprint::GOLD_ONLY_LEVEL_11,
            12 => ChestBlueprint::GOLD_ONLY_LEVEL_12,
        ])->map(function ($referenceID, $level) {
            $minGold = 100 * $level**2;
            $maxGold = (int) $minGold * 5 * ceil($level/4);
            return [
                'reference_id' => $referenceID,
                'quality' => (int) ceil($level/2),
                'size' => 1,
                'min_gold' => $minGold,
                'max_gold' => $maxGold,
                'item_blueprints' => []
            ];
        })->values();
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
            'quality' => $quality,
            'size' => $size,
            'min_gold' => $minGold = (int) ceil(25 * ($size) * ($quality**2)),
            'max_gold' => 5 * ($size + $quality - 2) * $minGold,
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
     * @param int $size
     * @return array
     */
    protected function getMidTierWarriorItemBlueprintArrays(int $size): array
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
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_ITEM,
                'chance' => 10,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_SWORD,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_AXE,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_MACE,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_POLEARM,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_TWO_HAND_SWORD,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_TWO_HAND_AXE,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_SHIELD,
                'chance' => 35,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_HELMET,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_HEAVY_ARMOR,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_GAUNTLETS,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_BELT,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_BOOTS,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_NECKLACE,
                'chance' => 10,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_BRACELET,
                'chance' => 10,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_RING,
                'chance' => 10,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_CROWN,
                'chance' => 10,
                'count' => $size - 1
            ],
        ];
    }
    /**
     * @param int $size
     * @return array
     */
    protected function getHighTierWarriorItemBlueprintArrays(int $size): array
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
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_ITEM,
                'chance' => 10,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_SWORD,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_AXE,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_MACE,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_POLEARM,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_TWO_HAND_SWORD,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_TWO_HAND_AXE,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_SHIELD,
                'chance' => 35,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_HELMET,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_HEAVY_ARMOR,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_GAUNTLETS,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_BELT,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_BOOTS,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_NECKLACE,
                'chance' => 10,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_BRACELET,
                'chance' => 10,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_RING,
                'chance' => 10,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_CROWN,
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
    protected function getMidTierRangerItemBlueprintArrays($size): array
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
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_ITEM,
                'chance' => 10,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_DAGGER,
                'chance' => 25,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_BOW,
                'chance' => 40,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_CROSSBOW,
                'chance' => 40,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_THROWING_WEAPON,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_LIGHT_ARMOR,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_HELMET,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_GAUNTLETS,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_BELT,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_LEGGINGS,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_NECKLACE,
                'chance' => 10,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_BRACELET,
                'chance' => 10,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_RING,
                'chance' => 10,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_CROWN,
                'chance' => 10,
                'count' => $size - 1
            ],
        ];
    }
    /**
     * @param $size
     * @return array
     */
    protected function getHighTierRangerItemBlueprintArrays($size): array
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
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_ITEM,
                'chance' => 10,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_DAGGER,
                'chance' => 25,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_BOW,
                'chance' => 40,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_CROSSBOW,
                'chance' => 40,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_THROWING_WEAPON,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_LIGHT_ARMOR,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_HELMET,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_GAUNTLETS,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_BELT,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_LEGGINGS,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_NECKLACE,
                'chance' => 10,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_BRACELET,
                'chance' => 10,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_RING,
                'chance' => 10,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_CROWN,
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

    /**
     * @param $size
     * @return array
     */
    protected function getMidTierSorcererBlueprintArrays($size): array
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
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_ITEM,
                'chance' => 10,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_WAND,
                'chance' => 30,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_ORB,
                'chance' => 30,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_STAFF,
                'chance' => 30,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_PSIONIC_ONE_HAND,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_PSIONIC_TWO_HAND,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_PSIONIC_SHIELD,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_CAP,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_ROBES,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_GLOVES,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_SHOES,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_SASH,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_BRACELET,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_RING,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_MID_TIER_CROWN,
                'chance' => 15,
                'count' => $size - 1
            ],
        ];
    }

    /**
     * @param $size
     * @return array
     */
    protected function getHighTierSorcererBlueprintArrays($size): array
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
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_ITEM,
                'chance' => 10,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_WAND,
                'chance' => 30,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_ORB,
                'chance' => 30,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_STAFF,
                'chance' => 30,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_PSIONIC_ONE_HAND,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_PSIONIC_TWO_HAND,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_PSIONIC_SHIELD,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_CAP,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_ROBES,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_GLOVES,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_SHOES,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_SASH,
                'chance' => 20,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_BRACELET,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_RING,
                'chance' => 15,
                'count' => $size - 1
            ],
            [
                'reference_id' => ItemBlueprint::RANDOM_ENCHANTED_HIGH_TIER_CROWN,
                'chance' => 15,
                'count' => $size - 1
            ],
        ];
    }
}
