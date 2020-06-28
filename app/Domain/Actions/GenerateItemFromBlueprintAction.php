<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 7/17/19
 * Time: 8:31 PM
 */

namespace App\Domain\Actions;


use App\Aggregates\ItemAggregate;
use App\Domain\Collections\AttackCollection;
use App\Domain\Models\Attack;
use App\Domain\Models\Enchantment;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\ItemClass;
use App\Domain\Models\ItemType;
use App\Domain\Models\Material;
use App\Exceptions\InvalidItemBlueprintException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class GenerateItemFromBlueprintAction
{

    /**
     * @param ItemBlueprint $itemBlueprint
     * @return Item
     */
    public function execute(ItemBlueprint $itemBlueprint): Item
    {
        $itemClass = $this->getItemClass($itemBlueprint);
        $itemType = $this->getItemType($itemBlueprint);
        $materialType = $this->getMaterial($itemBlueprint, $itemType->itemBase);

        /** @var Item $item */
        $item = Item::query()->create([
            'uuid' => (string) Str::uuid(),
            'item_class_id' => $itemClass->id,
            'item_type_id' => $itemType->id,
            'material_id' => $materialType->id,
            'item_blueprint_id' => $itemBlueprint->id,
            'name' => $itemBlueprint->item_name
        ]);

        $enchantments = $this->getEnchantments($itemBlueprint, $item->itemClass->name);
        $enchantments->each(function (Enchantment $enchantment) use ($item) {
            $item->enchantments()->save($enchantment);
        });

        $itemBlueprint->attacks->each(function (Attack $attack) use ($item) {
            $item->attacks()->save($attack);
        });

        return $item->fresh();
    }

    protected function getItemClass(ItemBlueprint $itemBlueprint): ItemClass
    {
        $itemClasses = $itemBlueprint->itemClasses;
        if ($itemClasses->count() > 0) {
            return $itemClasses->random();
        }
        $itemClassName =  count($itemBlueprint->enchantments) > 0 ? ItemClass::ENCHANTED : ItemClass::GENERIC;
        /** @var ItemClass $itemClass */
        $itemClass = ItemClass::query()->where('name', '=', $itemClassName)->first();
        return $itemClass;
    }

    protected function getItemType(ItemBlueprint $itemBlueprint): ItemType
    {
        $itemTypes = $itemBlueprint->itemTypes;
        if ($itemTypes->isNotEmpty()) {
            return $itemTypes->random();
        }

        $itemBases = $itemBlueprint->itemBases;
        if ($itemBases->isNotEmpty()) {
            return $this->getItemTypeFromBases($itemBases);
        }

        return $this->getRandomItemType();
    }

    /**
     * @param Collection $itemBases
     * @return ItemType
     */
    protected function getItemTypeFromBases(Collection $itemBases): ItemType
    {
        /** @var ItemBase $randomItemBase */
        $randomItemBase = $itemBases->random();

        return $this->getRandomItemType($randomItemBase->itemTypes);
    }

    protected function getMaterial(ItemBlueprint $itemBlueprint, ItemBase $itemBase): Material
    {
        $material = null;
        $materials = $itemBlueprint->materials;

        if ($materials->isNotEmpty()) {
            return $materials->random();
        }

        // No materials attached to blueprint, so we'll retrieve all for the item base
        $materialsForBase = $materialIDsForBase = Material::query()->whereHas('materialType', function (Builder $builder) use ($itemBase) {
            return $builder->whereIn('id', $itemBase->materialTypes()->pluck('id')->toArray());
        })->get();

        $randRangeMax = 8100; // 90 ^ 2
        $num = rand(1, $randRangeMax);
        // Get a number between 10 and 100 weighted towards the lower bound
        $maxMaterialGrade = 100 - sqrt($num);

        $materials = $materialsForBase->filter(function (Material $material) use ($maxMaterialGrade) {
            return $material->grade <= $maxMaterialGrade;
        });

        if ($materials->isNotEmpty()) {
            return $materials->random();
        }

        // If we still don't have a material, as a last ditch effort, we'll grab the lowest grade one
        /** @var Material $material */
        $material = $materialsForBase->sortBy(function (Material $material) {
            return $material->grade;
        })->first();

        return $material;
    }


    protected function getEnchantments(ItemBlueprint $itemBlueprint, string $itemClassName)
    {
        /** @var Item $item */
        if ($itemBlueprint->enchantments->count() > 0) {

            return $itemBlueprint->enchantments;

        } elseif ($itemClassName === ItemClass::ENCHANTED) {

            return $this->findEnchantments($itemBlueprint);
        }
        return collect();
    }

    protected function findEnchantments(ItemBlueprint $itemBlueprint)
    {
        $enchantmentsPower = $itemBlueprint->enchantment_power ?: $this->getRandomEnchantmentPower();
        $maxBoostLevel = $this->getMaxBoostLevel($enchantmentsPower);
        $enchantments = collect();

        while ($enchantmentsPower > 0) {

            /** @var Enchantment $enchantment */
            $enchantment = Enchantment::query()
                ->whereDoesntHave('measurableBoosts', function (Builder $builder) use ($maxBoostLevel) {
                $builder->where('boost_level', '>', $maxBoostLevel);
            })->where('restriction_level', '=', 0)
                ->inRandomOrder()
                ->first();
            $enchantments->push($enchantment);

            $enchantmentsPower -= $enchantment->measurableBoosts->boostLevelSum();
        }

        return $enchantments->unique();
    }

    protected function getRandomEnchantmentPower()
    {
        /*
         * Will return a number between 1 and 100, weighted heavily toward the lower-bound side
         */
        return (int) max(ceil(100.1 - sqrt(rand(1, 10000))), 1);
    }

    protected function getMaxBoostLevel(int $enchantmentPower)
    {
        /*
         * With higher enchantment power, we want to guarantee a higher max-boost-level
         * Assume max enchantment power is 100
         */
        $sqrtMax = (int) max(ceil(2501 - ($enchantmentPower**2/4)), 1);
        /*
         * Will return a number between 1 and 50, weighted heavily toward the lower-bound side
         */
        return (int) max(ceil(50.1 - sqrt(rand(1, $sqrtMax))), 1);
    }

    protected function getRandomItemType(Collection $itemTypes = null)
    {
        $itemTypes = $itemTypes ?: ItemType::all();

        $randRangeMax = 36; // 6 ^ 2
        $num = rand(1, $randRangeMax);
        // Get a number between 1 and 6 weighted towards the lower bound
        $maxItemTypeTier = (int) ceil(7 - sqrt($num));

        $filtered = $itemTypes->filter(function (ItemType $itemType) use ($maxItemTypeTier) {
            return $itemType->tier <= $maxItemTypeTier;
        });

        if ($filtered->isNotEmpty()) {
            return $filtered->random();
        }

        return $itemTypes->random();
    }

}
