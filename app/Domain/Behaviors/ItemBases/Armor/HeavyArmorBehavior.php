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

class HeavyArmorBehavior extends ArmorBehavior
{
    protected $validGearSlotTypes = [
        GearSlot::TORSO
    ];

    protected $weightModifier = 11;
    protected $protectionModifier = 7.5;
}
