<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/7/18
 * Time: 2:27 PM
 */

namespace App\Domain\Collections;


use App\Domain\Interfaces\Slottable;
use App\Domain\Models\Attack;
use App\Domain\Models\Enchantment;
use App\Domain\Models\Item;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ItemCollection
 * @package App\Domain\Collections
 *
 * @method Item shift()
 */
class ItemCollection extends Collection
{

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

    public function sumOfWeight()
    {
        return $this->sum(function (Item $item) {
            return $item->weight();
        });
    }

    public function getAttacks()
    {
        $attacks = new AttackCollection();
        $this->loadMissing('attacks')->each(function (Item $item) use ($attacks) {
            $item->attacks->each(function (Attack $attack) use ($attacks) {
                $attacks->push($attack);
            });
        });
        return $attacks;
    }
}
