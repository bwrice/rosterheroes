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

class GauntletsBehavior extends ArmorBehavior
{
    protected $validGearSlotTypes = [
        GearSlot::HANDS
    ];

    protected $weightModifier = 2.8;
    protected $protectionModifier = 1.75;
}
