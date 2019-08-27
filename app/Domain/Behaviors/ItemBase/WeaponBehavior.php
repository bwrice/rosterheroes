<?php


namespace App\Domain\Behaviors\ItemBase;


use App\Domain\Behaviors\ItemGroup\WeaponGroup;
use App\Domain\Models\SlotType;

abstract class WeaponBehavior extends ItemBaseBehavior
{
    public function __construct(WeaponGroup $weaponGroup)
    {
        parent::__construct($weaponGroup);
    }

    public function getSlotTypeNames(): array
    {
        return [
            SlotType::LEFT_ARM,
            SlotType::RIGHT_ARM
        ];
    }
}
