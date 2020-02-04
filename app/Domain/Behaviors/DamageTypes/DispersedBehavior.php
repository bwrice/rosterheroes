<?php


namespace App\Domain\Behaviors\DamageTypes;


class DispersedBehavior extends DamageTypeBehavior
{

    public function getCombatSpeedBonus(?int $fixedTargetCount)
    {
        return .25;
    }

    public function getBaseDamageBonus(?int $fixedTargetCount)
    {
        return 1.5;
    }

    public function getDamageMultiplierBonus(?int $fixedTargetCount)
    {
        return 1.5;
    }

    public function getMaxTargetCount(int $grade, ?int $fixedTargetCount)
    {
        return (int) (4 + ceil($grade/15));
    }

    public function getDamagePerTarget(int $damage, int $targetsCount)
    {
        if ($targetsCount > 0) {
            return (int) ceil($damage/$targetsCount);
        }
        return 0;
    }
}
