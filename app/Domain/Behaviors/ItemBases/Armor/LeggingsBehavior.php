<?php


namespace App\Domain\Behaviors\ItemBases\Armor;


use App\Domain\Behaviors\ItemBases\Armor\ArmorBehavior;
use App\Domain\Models\SlotType;
use App\Domain\Models\Support\GearSlots\GearSlot;

class LeggingsBehavior extends ArmorBehavior
{
    protected $validGearSlotTypes = [
        GearSlot::LEGS
    ];

    protected $weightModifier = 3.8;
    protected $protectionModifier = 2.75;
}
