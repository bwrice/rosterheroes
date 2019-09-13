<?php


namespace App\Domain\Behaviors\TargetRanges;


class MeleeRangeBehavior extends CombatPositionBehavior
{

    public function adjustCombatSpeed(float $speed): float
    {
        return $speed;
    }

    public function adjustBaseDamage(float $baseDamage): float
    {
        return $baseDamage;
    }

    public function adjustDamageMultiplier(float $damageModifier): float
    {
        return $damageModifier;
    }
}
