<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/18/18
 * Time: 9:27 PM
 */

namespace App\Domain\Behaviors\ItemBases\Clothing;


use App\Domain\Behaviors\ItemBases\Clothing\ClothingBehavior;
use App\Domain\Models\SlotType;
use App\Domain\Models\Support\GearSlots\GearSlot;

class SashBehavior extends ClothingBehavior
{
    protected $validGearSlotTypes = [
        GearSlot::WAIST
    ];

    protected $weightModifier = 1.3;
    protected $protectionModifier = 1;
}
