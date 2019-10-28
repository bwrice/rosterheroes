<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:25 PM
 */

namespace App\Domain\Behaviors\ItemBases\Armor;


use App\Domain\Behaviors\ItemBases\Armor\ArmorBehavior;
use App\Domain\Models\SlotType;
use App\Domain\Models\Support\GearSlots\GearSlot;

class HelmetBehavior extends ArmorBehavior
{
    protected $validGearSlotTypes = [
        GearSlot::HEAD
    ];

    protected $weightModifier = 2.9;
    protected $protectionModifier = 2.1;
}
