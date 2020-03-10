<?php


namespace App\Domain\Behaviors\ItemBases\Weapons\ArmBehaviors;


class TwoArmBehavior implements ArmBehaviorInterface
{

    public function getSlotsCount(): int
    {
        return 2;
    }

    public function getDamageMultiplierModifierBonus(): float
    {
        return 1.8;
    }

    public function getBaseDamageModifierBonus(): float
    {
        return 1.8;
    }

    public function getCombatSpeedModifierBonus(): float
    {
        return 0;
    }
}
