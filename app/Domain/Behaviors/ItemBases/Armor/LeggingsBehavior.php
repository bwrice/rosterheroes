<?php


namespace App\Domain\Behaviors\ItemBases\Armor;


use App\Domain\Behaviors\ItemBases\Armor\ArmorBehavior;
use App\Domain\Models\SlotType;

class LeggingsBehavior extends ArmorBehavior
{
    protected $weightModifier = 3.8;
    protected $protectionModifier = 2.75;

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
