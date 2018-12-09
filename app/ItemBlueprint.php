<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ItemBlueprint
 * @package App
 *
 * @property int $id
 * @property string $item_name
 *
 * @property Collection $enchantments
 * @property ItemClass $itemClass
 * @property ItemType $itemType
 * @property ItemBase $itemBase
 * @property ItemGroup $itemGroup
 * @property MaterialType $materialType
 */
class ItemBlueprint extends Model
{
    protected $guarded = [];

    public function itemClass()
    {
        return $this->belongsTo(ItemClass::class);
    }

    public function itemType()
    {
        return $this->belongsTo(ItemType::class);
    }

    public function materialType()
    {
        return $this->belongsTo(MaterialType::class);
    }

    public function itemBase()
    {
        return $this->belongsTo(ItemBase::class);
    }

    public function itemGroup()
    {
        return $this->belongsTo(ItemGroup::class);
    }

    public function enchantments()
    {
        return $this->belongsToMany(Enchantment::class, 'enchantment_item_blueprint', 'blueprint_id', 'ench_id');
    }

    /**
     * @return Item
     */
    public function generate()
    {
        $itemClass = $this->getItemClass();
        $itemType = $this->getItemType();
        $materialType = $this->getMaterialType($itemType);

        /** @var Item $item */
        $item = Item::create([
            'item_class_id' => $itemClass->id,
            'item_type_id' => $itemType->id,
            'material_type_id' => $materialType->id,
            'item_blueprint_id' => $this->id,
            'name' => $this->item_name
        ]);

        $this->attachEnchantments($item, $itemClass, $this->enchantments, $this->enchantments_power);

        //TODO when attacks are figured out and seeded/attached
//        $this->attachAttacks($item, $itemType, $this->attacks, $this->attacks_power);

        return $item->fresh();
    }

    /**
     * @return ItemClass
     */
    protected function getItemClass()
    {
        if ($this->itemClass) {
            return $this->itemClass;
        }
        $className =  count($this->enchantments) > 0 ? 'enchanted' : 'generic';
        return ItemClass::where('name', '=', $className)->first();
    }

    /**
     * @return ItemType
     */
    protected function getItemType()
    {
        if ($this->itemType) {
            return $this->itemType;
        }

        if ($this->itemBase) {
            return $this->getItemTypeFromBase($this->itemBase);
        }

        if ($this->itemGroup) {
            return $this->getItemTypeFromGroup($this->itemGroup);
        }

        return ItemType::inRandomOrder()->first();
    }

    /**
     * @param ItemType $itemType
     * @return MaterialType
     */
    protected function getMaterialType(ItemType $itemType)
    {
        $materialType = $this->materialType;

        if (!$materialType || !self::verifyMaterialType($itemType, $materialType)) {
            $materialType = $itemType->materialTypes()->inRandomOrder()->first();
        }

        return $materialType;
    }

    /**
     * @param Item $item
     * @param $itemClass
     * @param $enchantments
     * @param $enchantmentsPower
     * @return Item
     */
    protected function attachEnchantments(Item $item, $itemClass, Collection $enchantments, $enchantmentsPower)
    {
        /** @var Item $item */
        if ($enchantments->count() > 0) {
            $item->enchantments()->saveMany($enchantments);

        } else if ($itemClass == 'enchanted') {

            $enchantments = $this->getEnchantments($enchantmentsPower);
            $item->enchantments()->saveMany($enchantments);
        }

        return $item;
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

            $attacksPower -= $attack->grade;
        }

        return $attacksToAttach;
    }


    /**
     * @param $enchantmentsPower
     * @return \Illuminate\Support\Collection
     */
    protected function getEnchantments($enchantmentsPower)
    {
        $enchantmentsPower = $enchantmentsPower > 0 ? $enchantmentsPower : 10;

        $enchantments = collect();

        while ($enchantmentsPower > 0) {

            $enchantment = Enchantment::inRandomOrder()->first();

            $enchantments->push($enchantment);

            $enchantmentsPower -= $enchantment->grade;
        }

        return $enchantments;
    }

    /**
     * @param ItemType $itemType
     * @param MaterialType $materialType
     * @return bool
     */
    public static function verifyMaterialType(ItemType $itemType, MaterialType $materialType)
    {
        return in_array($materialType->id, $itemType->materialTypes()->pluck('id')->toArray());
    }

    /**
     * @param ItemGroup $itemGroup
     * @return ItemType
     */
    protected function getItemTypeFromGroup(ItemGroup $itemGroup)
    {
        /** @var ItemBase $itemBase */
        $itemBase = $itemGroup->itemBases()->inRandomOrder()->first();

        if (!$itemBase) {
            $itemBase = ItemBase::inRandomOrder()->first();
        }

        return $this->getItemTypeFromBase($itemBase);
    }

    /**
     * @param ItemBase|null $itemBase
     * @return ItemType
     */
    protected function getItemTypeFromBase(ItemBase $itemBase)
    {
        $itemType = $itemBase->itemTypes()->inRandomOrder()->first();

        if (!$itemType) {
            $itemType = ItemType::inRandomOrder()->first();
        }

        return $itemType;
    }
}
