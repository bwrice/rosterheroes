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
        $materialType = $this->getMaterial($itemBlueprint, $itemType);

        $uuid = Str::uuid();
        /** @var ItemAggregate $itemAggregate */
        $itemAggregate = ItemAggregate::retrieve($uuid);
        $itemAggregate->createItem($itemClass->id, $itemType->id, $materialType->id, $itemBlueprint->id, $itemBlueprint->item_name);
        $itemAggregate->persist();

        $item = Item::findUuid($uuid);

        $enchantments = $this->getEnchantments($itemBlueprint, $item->itemClass->name);
        $enchantments->each(function (Enchantment $enchantment) use ($itemAggregate) {
            $itemAggregate->attachEnchantment($enchantment->id);
        });

        $itemBlueprint->attacks->each(function (Attack $attack) use ($itemAggregate) {
            $itemAggregate->attachAttack($attack->id);
        });

        $itemAggregate->persist(); // persist enchantment and attack events
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
        $enchantmentsPower = $itemBlueprint->enchantment_power ?: 10;

        $enchantments = collect();

        while ($enchantmentsPower > 0) {

            /** @var Enchantment $enchantment */
            $enchantment = Enchantment::query()->inRandomOrder()->first();
            $enchantments->push($enchantment);

            $enchantmentsPower -= $enchantment->measurableBoosts->boostLevelSum();
        }

        return $enchantments->unique();
    }

}
