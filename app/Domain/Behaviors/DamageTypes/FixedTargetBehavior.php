<?php


namespace App\Domain\Behaviors\DamageTypes;


class FixedTargetBehavior extends DamageTypeBehavior
{

    public function getCombatSpeedBonus(?int $fixedTargetCount)
    {
        return 1/sqrt($fixedTargetCount);
    }

    public function getBaseDamageBonus(?int $fixedTargetCount)
    {
        return 1/sqrt($fixedTargetCount);
    }

    public function getDamageMultiplierBonus(?int $fixedTargetCount)
    {
        return 1/sqrt($fixedTargetCount);
    }

    public function getMaxTargetCount(int $grade, ?int $fixedTargetCount)
    {
        return $fixedTargetCount ?: 1;
    }
}
