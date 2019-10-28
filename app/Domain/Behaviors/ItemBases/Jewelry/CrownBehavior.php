<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:30 PM
 */

namespace App\Domain\Behaviors\ItemBases\Jewelry;


use App\Domain\Behaviors\ItemBases\Jewelry\JewelryBehavior;
use App\Domain\Models\SlotType;
use App\Domain\Models\Support\GearSlots\GearSlot;

class CrownBehavior extends JewelryBehavior
{
    protected $validGearSlotTypes = [
        GearSlot::HEAD
    ];

    protected $weightModifier = 2.5;
}
