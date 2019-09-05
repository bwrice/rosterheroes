<?php


namespace App\Domain\Behaviors\TargetRanges;


class LongRangeBehavior extends TargetRangeBehavior
{

    public function adjustCombatSpeed(float $speed): float
    {
        return $speed/1.75;
    }

    public function adjustBaseDamage(float $baseDamage): float
    {
        return $baseDamage/1.75;
    }

    public function adjustDamageModifier(float $damageModifier): float
    {
        return $damageModifier/1.75;
    }
}
