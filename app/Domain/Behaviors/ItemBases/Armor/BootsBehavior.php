<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:26 PM
 */

namespace App\Domain\Behaviors\ItemBases\Armor;


use App\Domain\Models\Support\GearSlots\GearSlot;

class BootsBehavior extends ArmorBehavior
{
    protected $validGearSlotTypes = [
        GearSlot::FEET
    ];

    protected $weightModifier = 2.6;
    protected $protectionModifier = 1.6;
}
