<?php


namespace App\Domain\Behaviors\DamageTypes;


class FixedTargetBehavior extends DamageTypeBehavior
{
    public function getMaxTargetCount(int $grade, ?int $fixedTargetCount)
    {
        return $fixedTargetCount ?: 1;
    }

    public function getDamagePerTarget(int $damage, int $targetsCount)
    {
        return $damage;
    }

    public function getInitialBaseDamage(int $tier, ?int $targetsCount): float
    {
        $targetsCount = $targetsCount ?: 1;
        $tierMultiplier = sqrt($tier);
        $targetsCountMultiplier = 1/(1 + .25 * ($targetsCount - 1));
        return 10 * $tierMultiplier * $targetsCountMultiplier;
    }

    public function getInitialDamageMultiplier(int $tier, ?int $targetsCount): float
    {
        $targetsCount = $targetsCount ?: 1;
        $tierMultiplier = sqrt($tier);
        $targetsCountMultiplier = 1/(1 + .25 * ($targetsCount - 1));
        return 2 * $tierMultiplier * $targetsCountMultiplier;
    }

    public function getInitialCombatSpeed(int $tier, ?int $targetsCount): float
    {
        $targetsCount = $targetsCount ?: 1;
        $tierMultiplier = 1/sqrt($tier);
        $targetsCountMultiplier = 1/(1 + .5 * ($targetsCount - 1));
        return 10 * $tierMultiplier * $targetsCountMultiplier;
    }

    public function getResourceCostMagnitude(int $tier, ?int $targetsCount): float
    {
        return $tier * sqrt($targetsCount);
    }
}
