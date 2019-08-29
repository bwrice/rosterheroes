<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:19 PM
 */

namespace App\Domain\Behaviors\ItemBases;


class SwordBehavior extends WeaponBehavior
{

    public function getSlotsCount(): int
    {
        return 1;
    }
}
