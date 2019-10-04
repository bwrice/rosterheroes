<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 7/18/19
 * Time: 10:43 AM
 */

namespace App\Domain\Collections;


use App\Domain\Models\Enchantment;
use App\Domain\Models\Measurable;
use Illuminate\Database\Eloquent\Collection;

class EnchantmentCollection extends Collection
{
    /**
     * @return int
     */
    public function boostLevelSum()
    {
        return (int) $this->loadMissing('measurableBoosts')->sum(function (Enchantment $enchantment) {
            return $enchantment->boostLevelSum();
        });
    }

    public function getBoostAmount(Measurable $measurable)
    {
        return $this->sum(function (Enchantment $enchantment) use ($measurable) {
            return $measurable->measurableType->getBehavior()->getBoostMultiplier();
        });
    }
}
