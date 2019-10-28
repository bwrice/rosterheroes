<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:25 PM
 */

namespace App\Domain\Behaviors\ItemBases\Clothing;


use App\Domain\Behaviors\ItemBases\Clothing\ClothingBehavior;
use App\Domain\Models\SlotType;
use App\Domain\Models\Support\GearSlots\GearSlot;

class CapBehavior extends ClothingBehavior
{
    protected $validGearSlotTypes = [
        GearSlot::HEAD
    ];

    protected $weightModifier = 1;
    protected $protectionModifier = 1.25;
}
