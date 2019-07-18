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
use App\Domain\Models\ItemGroup;
use App\Domain\Models\ItemType;
use App\Domain\Models\MaterialType;
use App\Exceptions\InvalidItemBlueprintException;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class GenerateItemFromBlueprintAction
{
    /**
     * @var ItemBlueprint
     */
    private $itemBlueprint;

    public function __construct(ItemBlueprint $itemBlueprint)
    {
        $this->itemBlueprint = $itemBlueprint;
    }

    public function __invoke(): Item
    {
        $itemClass = $this->getItemClass();
        $itemType = $this->getItemType();
        $materialType = $this->getMaterialType($itemType);

        $uuid = Str::uuid();
        /** @var ItemAggregate $itemAggregate */
        $itemAggregate = ItemAggregate::retrieve($uuid);
        $itemAggregate->createItem($itemClass->id, $itemType->id, $materialType->id, $this->itemBlueprint->id, $this->itemBlueprint->item_name);
        $itemAggregate->persist();

        $item = Item::uuid($uuid);

        $enchantments = $this->getEnchantments($item->itemClass->name);
        $enchantments->each(function (Enchantment $enchantment) use ($itemAggregate) {
            $itemAggregate->attachEnchantment($enchantment->id);
        });

        // TODO attacks
        //$this->attachAttacks();

        $itemAggregate->persist(); // persist enchantment and attack events
        return $item->fresh();
    }

    /**
     * @return ItemClass
     */
    protected function getItemClass(): ItemClass
    {
        if ($this->itemBlueprint->itemClass) {
            return $this->itemBlueprint->itemClass;
        }
        $itemClassName =  count($this->itemBlueprint->enchantments) > 0 ? ItemClass::ENCHANTED : ItemClass::GENERIC;
        /** @var ItemClass $itemClass */
        $itemClass = ItemClass::query()->where('name', '=', $itemClassName)->first();
        return $itemClass;
    }

    /**
     * @return ItemType
     */
    protected function getItemType(): ItemType
    {
        if ($this->itemBlueprint->itemType) {
            return $this->itemBlueprint->itemType;
        }

        if ($this->itemBlueprint->itemBase) {
            return $this->getItemTypeFromBase($this->itemBlueprint->itemBase);
        }

        if ($this->itemBlueprint->itemGroup) {
            return $this->getItemTypeFromGroup($this->itemBlueprint->itemGroup);
        }

        /** @var ItemType $itemType */
        $itemType = ItemType::query()->inRandomOrder()->first();
        return $itemType;
    }

    /**
     * @param ItemGroup $itemGroup
     * @return ItemType
     */
    protected function getItemTypeFromGroup(ItemGroup $itemGroup): ItemType
    {
        /** @var ItemBase $itemBase */
        $itemBase = $itemGroup->itemBases()->inRandomOrder()->first();

        if (!$itemBase) {
            $itemBase = ItemBase::query()->inRandomOrder()->first();
        }

        return $this->getItemTypeFromBase($itemBase);
    }

    /**
     * @param ItemBase $itemBase
     * @return ItemType
     */
    protected function getItemTypeFromBase(ItemBase $itemBase): ItemType
    {
        $itemType = $itemBase->itemTypes()->inRandomOrder()->first();

        if (!$itemType) {
            $itemType = ItemType::query()->inRandomOrder()->first();
        }

        return $itemType;
    }

    /**
     * @param ItemType $itemType
     * @return MaterialType
     */
    protected function getMaterialType(ItemType $itemType): MaterialType
    {
        $materialType = $this->itemBlueprint->materialType;
        if ($materialType) {
            if ( ! in_array($materialType->id, $itemType->materialTypes()->pluck('id')->toArray())) {
                throw new InvalidItemBlueprintException($this->itemBlueprint);
            }
        } else {
            $materialType = $itemType->materialTypes()->inRandomOrder()->first();
        }

        return $materialType;
    }


    /**
     * @param string $itemClassName
     * @return Collection
     */
    protected function getEnchantments(string $itemClassName)
    {
        /** @var Item $item */
        if ($this->itemBlueprint->enchantments->count() > 0) {

            return $this->itemBlueprint->enchantments;

        } elseif ($itemClassName === ItemClass::ENCHANTED) {

            return $this->findEnchantments();
        }
        return collect();
    }

    /**
     * @return Collection
     */
    protected function findEnchantments()
    {
        $enchantmentsPower = $this->itemBlueprint->enchantment_power ?: 10;

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
     * @return \Illuminate\Support\Collection
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