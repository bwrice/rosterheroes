<?php


namespace App\Domain\Behaviors\DamageTypes;


class AreaOfEffectBehavior extends DamageTypeBehavior
{

    public function getMaxTargetCount(int $grade, ?int $fixedTargetCount)
    {
        return (int) (3 + ceil($grade/30));
    }

    public function getDamagePerTarget(int $damage, int $targetsCount)
    {
        return $damage;
    }

    public function getInitialBaseDamage(int $tier, ?int $targetsCount): float
    {
        return sqrt($tier) * 20;
    }
}
