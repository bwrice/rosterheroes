<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:24 PM
 */

namespace App\Domain\Behaviors\ItemBases\Shields;



class ShieldBehavior extends ShieldGroupBehavior
{
    protected $weightModifier = 24;

    public function getSlotsCount(): int
    {
        return 1;
    }
}
