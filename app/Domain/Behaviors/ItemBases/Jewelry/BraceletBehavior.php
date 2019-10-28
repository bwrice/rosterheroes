<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:28 PM
 */

namespace App\Domain\Behaviors\ItemBases\Jewelry;


use App\Domain\Behaviors\ItemBases\Jewelry\JewelryBehavior;
use App\Domain\Models\SlotType;
use App\Domain\Models\Support\GearSlots\GearSlot;

class BraceletBehavior extends JewelryBehavior
{
    protected $validGearSlotTypes = [
        GearSlot::OFF_WRIST,
        GearSlot::PRIMARY_WRIST,
    ];

    protected $weightModifier = 1.3;
}
