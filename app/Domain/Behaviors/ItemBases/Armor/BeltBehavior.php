<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:27 PM
 */

namespace App\Domain\Behaviors\ItemBases\Armor;

use App\Domain\Models\Support\GearSlots\GearSlot;

class BeltBehavior extends ArmorBehavior
{
    protected $validGearSlotTypes = [
        GearSlot::WAIST
    ];

    protected $weightModifier = 2.4;
    protected $protectionModifier = 1.5;
}
