<?php


namespace App\Domain\Behaviors\TargetRanges;


class MeleeRangeBehavior extends TargetRangeBehavior
{

    public function adjustCombatSpeed(float $speed): float
    {
        return $speed;
    }

    public function adjustBaseDamage(float $baseDamage): float
    {
        return $baseDamage;
    }

    public function adjustDamageModifier(float $damageModifier): float
    {
        return $damageModifier;
    }
}
