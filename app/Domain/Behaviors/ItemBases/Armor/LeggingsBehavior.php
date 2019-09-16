<?php


namespace App\Domain\Behaviors\ItemBases\Armor;


use App\Domain\Behaviors\ItemBases\Armor\ArmorBehavior;
use App\Domain\Models\SlotType;

class LeggingsBehavior extends ArmorBehavior
{
    protected $weightMultiplier = 3.8;

    public function getSlotTypeNames(): array
    {
        return [
            SlotType::LEGS
        ];
    }

    public function getSlotsCount(): int
    {
        return 1;
    }
}
