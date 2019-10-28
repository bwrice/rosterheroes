<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:26 PM
 */

namespace App\Domain\Behaviors\ItemBases\Armor;


use App\Domain\Behaviors\ItemBases\Armor\ArmorBehavior;
use App\Domain\Models\SlotType;
use App\Domain\Models\Support\GearSlots\GearSlot;

class LightArmorBehavior extends ArmorBehavior
{
    protected $validGearSlotTypes = [
        GearSlot::TORSO
    ];

    protected $weightModifier = 6.5;
    protected $protectionModifier = 5.25;
}
