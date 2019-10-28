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

class NecklaceBehavior extends JewelryBehavior
{
    protected $validGearSlotTypes = [
        GearSlot::NECK
    ];

    protected $weightModifier = 1.5;
}
