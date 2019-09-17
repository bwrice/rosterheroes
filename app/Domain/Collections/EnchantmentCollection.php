<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 7/18/19
 * Time: 10:43 AM
 */

namespace App\Domain\Collections;


use App\Domain\Models\Enchantment;
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
}
