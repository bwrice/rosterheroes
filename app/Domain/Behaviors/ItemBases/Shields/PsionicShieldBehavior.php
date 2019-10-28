<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:25 PM
 */

namespace App\Domain\Behaviors\ItemBases\Shields;


use App\Domain\Behaviors\ItemBases\Shields\ShieldGroupBehavior;
use App\Domain\Models\SlotType;

class PsionicShieldBehavior extends ShieldGroupBehavior
{
    protected $weightModifier = 14;
    protected $protectionModifier = 15;
    protected $blockChanceModifier = 2.8;

    public function getGearSlotsCount(): int
    {
        return 1;
    }
}
