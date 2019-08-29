<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:23 PM
 */

namespace App\Domain\Behaviors\ItemBases;


class StaffBehavior extends WeaponBehavior
{

    public function getSlotsCount(): int
    {
        return 2;
    }
}
