<?php


namespace App\Domain\Behaviors\ItemBases\Shields;

use App\Domain\Behaviors\ItemBases\ItemBaseBehavior;
use App\Domain\Behaviors\ItemGroup\ShieldGroup;
use App\Domain\Models\SlotType;

abstract class ShieldGroupBehavior extends ItemBaseBehavior
{
    public function __construct(ShieldGroup $shieldGroup)
    {
        parent::__construct($shieldGroup);
    }

    public function getSlotTypeNames(): array
    {
        return [
            SlotType::LEFT_ARM
        ];
    }

    public function getDamageMultiplierModifier(): float
    {
        return 1;
    }

    public function getBaseDamageModifier(): float
    {
        return 1;
    }

    public function getCombatSpeedModifier(): float
    {
        return 1;
    }

}
