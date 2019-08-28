<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 7/17/19
 * Time: 8:31 PM
 */

namespace App\Domain\Actions;


use App\Aggregates\ItemAggregate;
use App\Domain\Models\Enchantment;
use App\Domain\Models\Item;
use App\Domain\Models\ItemBase;
use App\Domain\Models\ItemBlueprint;
use App\Domain\Models\ItemClass;
use App\Domain\Models\ItemType;
use App\Domain\Models\MaterialType;
use App\Exceptions\InvalidItemBlueprintException;
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
        $materialType = $this->getMaterialType($itemBlueprint, $itemType);

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

        // TODO attacks
        //$this->attachAttacks();

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

    protected function getMaterialType(ItemBlueprint $itemBlueprint, ItemType $itemType): MaterialType
    {
        $randomMaterialType = null;
        $materialTypes = $itemBlueprint->materialTypes;

        if ($materialTypes->count() > 0) {
            $materialTypeIDs = $itemType->materialTypes()->pluck('id')->toArray();
            $randomMaterialType = $materialTypes->shuffle()->first(function (MaterialType $materialType) use ($materialTypeIDs) {
                return in_array($materialType->id, $materialTypeIDs);
            });
        }

        $randomMaterialType = $randomMaterialType ?: $itemType->materialTypes()->inRandomOrder()->first();

        return $randomMaterialType;
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

            $enchantmentsPower -= $enchantment->boostLevelSum();
        }

        return $enchantments;
    }


    /**
     * @param Item $item
     * @param ItemType $itemType
     * @param $attacks
     * @param $attacksPower
     * @return Item
     */
    protected function attachAttacks(Item $item, ItemType $itemType, Collection $attacks, $attacksPower)
    {

        if ($attacks->count() == 0) {
            $attacks = $this->getAttacks($itemType, $attacksPower);
        }

        $item->attacks()->saveMany($attacks);

        return $item;
    }

    /**
     * @param ItemType $itemType
     * @param $attacksPower
     * @return Collection
     */
    protected function getAttacks(ItemType $itemType, $attacksPower)
    {
        //TODO what to do if itemType doesn't support attacks
        $attacksPower = $attacksPower > 0 ? $attacksPower : $itemType->grade;

        $attacks = $itemType->attacks()->get()->shuffle();

        $attacksToAttach = collect();

        while ($attacksPower > 0 && $attacks->count() > 0) {

            $attack = $attacks->shift();
            $attacksToAttach->push($attack);

            //TODO figure out attack grade?
            $attacksPower -= $attack->grade;
        }

        return $attacksToAttach;
    }

}
