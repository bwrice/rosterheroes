<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:22 PM
 */

namespace App\Domain\Behaviors\ItemBase;


class PoleArmBehavior extends WeaponBehavior
{

    public function getSlotsCount(): int
    {
        return 2;
    }
}
