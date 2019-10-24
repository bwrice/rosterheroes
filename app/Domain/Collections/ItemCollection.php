<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/7/18
 * Time: 2:27 PM
 */

namespace App\Domain\Collections;


use App\Domain\Interfaces\Slottable;
use App\Domain\Models\Enchantment;
use App\Domain\Models\Item;
use Illuminate\Database\Eloquent\Collection;

class ItemCollection extends Collection
{
    public function getSlots(): SlotCollection
    {
        $slotCollection = new SlotCollection();
        $this->loadMissing('slots')->each(function (Item $item) use (&$slotCollection) {
            $slotCollection = $slotCollection->merge($item->getSlots());
        });
        return $slotCollection;
    }

    public function getEnchantments(): EnchantmentCollection
    {
        $enchantmentCollection = new EnchantmentCollection();
        $this->loadMissing('enchantments')->each(function (Item $item) use ($enchantmentCollection) {
            $item->enchantments->each(function (Enchantment $enchantment) use ($enchantmentCollection) {
                $enchantmentCollection->push($enchantment);
            });
        });
        return $enchantmentCollection;
    }
}
