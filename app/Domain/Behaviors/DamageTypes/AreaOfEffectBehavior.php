<?php


namespace App\Domain\Behaviors\DamageTypes;


class AreaOfEffectBehavior extends DamageTypeBehavior
{

    public function getCombatSpeedBonus(?int $fixedTargetCount)
    {
        return 0;
    }

    public function getBaseDamageBonus(?int $fixedTargetCount)
    {
        return 0;
    }

    public function getDamageMultiplierBonus(?int $fixedTargetCount)
    {
        return 0;
    }

    public function getMaxTargetCount(int $grade, ?int $fixedTargetCount)
    {
        return (int) (3 + ceil($grade/30));
    }
}
