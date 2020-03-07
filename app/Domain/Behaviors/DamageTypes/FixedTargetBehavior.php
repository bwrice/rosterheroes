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
}
