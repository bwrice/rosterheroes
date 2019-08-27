<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:21 PM
 */

namespace App\Domain\Behaviors\ItemBase;


class CrossbowBehavior extends WeaponBehavior
{

    public function getSlotsCount(): int
    {
        return 2;
    }
}
