<?php


namespace App\Domain\Behaviors\ItemBases\Weapons\ArmBehaviors;


class SingleArmBehavior implements ArmBehaviorInterface
{

    public function getSlotsCount(): int
    {
        return 1;
    }

    public function getDamageMultiplierModifierBonus(): float
    {
        return 0;
    }

    public function getBaseDamageModifierBonus(): float
    {
        return 0;
    }

    public function getCombatSpeedModifierBonus(): float
    {
        return .3;
    }

    public function getResourceCostAmountModifier(): float
    {
        return 1;
    }

    public function getResourceCostPercentModifier(): float
    {
        return 1;
    }
}
