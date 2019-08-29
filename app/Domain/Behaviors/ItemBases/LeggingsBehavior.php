<?php


namespace App\Domain\Behaviors\ItemBases;


use App\Domain\Models\SlotType;

class LeggingsBehavior extends ArmorBehavior
{

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
