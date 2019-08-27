<?php


namespace App\Domain\Behaviors\ItemBase;


use App\Domain\Models\SlotType;

abstract class WeaponBehavior extends ItemBaseBehavior
{
    public function getItemGroup(): string
    {
        return 'weapon';
    }

    public function getSlotTypeNames(): array
    {
        return [
            SlotType::LEFT_ARM,
            SlotType::RIGHT_ARM
        ];
    }
}
