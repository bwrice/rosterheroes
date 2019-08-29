<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:22 PM
 */

namespace App\Domain\Behaviors\ItemBases\Weapons;


use App\Domain\Behaviors\ItemBases\Weapons\WeaponBehavior;

class WandBehavior extends WeaponBehavior
{

    public function getSlotsCount(): int
    {
        return 1;
    }
}
