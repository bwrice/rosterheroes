<?php


namespace App\Domain\Behaviors\ItemBase;


use App\Domain\Models\SlotType;

abstract class ShieldGroupBehavior extends ItemBaseBehavior
{
    public function getItemGroup(): string
    {
        return 'shield';
    }

    public function getSlotTypeNames(): array
    {
        return [
            SlotType::LEFT_ARM
        ];
    }
}
