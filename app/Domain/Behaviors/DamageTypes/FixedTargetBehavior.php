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
        $targetsCountReduction = 1/(1 + .25 * ($targetsCount - 1));
        return 10 * $tierMultiplier * $targetsCountReduction;
    }
}
