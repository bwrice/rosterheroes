<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:20 PM
 */

namespace App\Domain\Behaviors\ItemBase;


class MaceBehavior extends WeaponBehavior
{

    public function getSlotsCount(): int
    {
        return 1;
    }
}
