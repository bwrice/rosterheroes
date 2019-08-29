<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:23 PM
 */

namespace App\Domain\Behaviors\ItemBases\Weapons;


use App\Domain\Behaviors\ItemBases\Weapons\WeaponBehavior;

class PsionicOneHandBehavior extends WeaponBehavior
{

    public function getSlotsCount(): int
    {
        return 1;
    }
}
