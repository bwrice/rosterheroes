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
        if ($itemTypes->count() > 0) {
            return $itemTypes->random();
        }

        $itemBases = $itemBlueprint->itemBases;
        if ($itemBases->count() > 0) {
            return $this->getItemTypeFromBases($itemBases);
        }

        /** @var ItemType $itemType */
        $itemType = ItemType::query()->inRandomOrder()->first();
        return $itemType;
    }

    /**
     * @param Collection $itemBases
     * @return ItemType
     */
    protected function getItemTypeFromBases(Collection $itemBases): ItemType
    {
        /** @var ItemBase $randomItemBase */
        $randomItemBase = $itemBases->random();

        $itemType = $randomItemBase->itemTypes()->inRandomOrder()->first();
        return $itemType;
    }

    protected function getMaterial(ItemBlueprint $itemBlueprint, ItemBase $itemBase): Material
    {
        $material = null;
        $materials = $itemBlueprint->materials;

        $materialQueryForBase = $materialIDsForBase = Material::query()->whereHas('materialType', function (Builder $builder) use ($itemBase) {
            return $builder->whereIn('id', $itemBase->materialTypes()->pluck('id')->toArray());
        });

        if ($materials->count() > 0) {
            $materialIDsForBase = $materialQueryForBase->pluck('id')->toArray();

            $material = $materials->shuffle()->first(function (Material $materialType) use ($materialIDsForBase) {
                return in_array($materialType->id, $materialIDsForBase);
            });
        }

        /** @var Material $material */
        $material = $material ?: $materialQueryForBase->inRandomOrder()->first();

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
            $enchantment = Enchantment::query()->inRandomOrder()->whereDoesntHave('measurableBoosts', function (Builder $builder) use ($maxBoostLevel) {
                $builder->where('boost_level', '>', $maxBoostLevel);
            })->first();
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

}
