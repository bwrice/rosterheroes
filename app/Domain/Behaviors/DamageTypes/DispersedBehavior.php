<?php


namespace App\Domain\Behaviors\DamageTypes;


class DispersedBehavior extends DamageTypeBehavior
{

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

    public function getInitialBaseDamage(int $tier, ?int $targetsCount): float
    {
        return sqrt($tier) * 100;
    }
}
