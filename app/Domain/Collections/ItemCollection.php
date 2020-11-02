<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/7/18
 * Time: 2:27 PM
 */

namespace App\Domain\Collections;

use App\Domain\Interfaces\UsesItems;
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

    public function getAttacks(): AttackCollection
    {
        $attacks = new AttackCollection();
        $this->loadMissing(['attacks', 'itemType.attacks'])->each(function (Item $item) use ($attacks) {
            $item->getAttacks()->each(function (Attack $attack) use ($attacks) {
                $attacks->push($attack);
            });
        });
        return $attacks;
    }

    public function sumOfWeight()
    {
        return $this->sum(function (Item $item) {
            return $item->weight();
        });
    }

    public function sortByWeight($desc = false)
    {
        $callBack = function (Item $item) {
            return $item->weight();
        };

        if ($desc) {
            return $this->sortByDesc($callBack);
        }
        return $this->sortBy($callBack);
    }

    /**
     * @return int
     */
    public function protection()
    {
        return $this->sum(function (Item $item) {
            return $item->getProtection();
        });
    }

    public function blockChance()
    {
        return $this->sum(function (Item $item) {
            return $item->getBlockChance();
        });
    }

    public function sortByMostRecentlyUpdated()
    {
        return $this->sortByDesc(function (Item $item) {
            return $item->updated_at->timestamp;
        });
    }

    public function shopAvailable()
    {
        return $this->filter(function (Item $item) {
            return ! is_null($item->made_shop_available_at);
        });
    }

    public function setUsesItems(UsesItems $usesItems)
    {
        $this->each(function (Item $item) use ($usesItems) {
            $item->setUsesItems($usesItems);
        });
        return $this;
    }

    public function staminaPerMoment()
    {
        return $this->sum(function (Item $item) {
            return $item->staminaPerMoment();
        });
    }

    public function manaPerMoment()
    {
        return $this->sum(function (Item $item) {
            return $item->manaPerMoment();
        });
    }
}
