<?php


namespace App\Domain\Behaviors\DamageTypes;


class AreaOfEffectBehavior extends DamageTypeBehavior
{

    public function adjustCombatSpeed(float $speed): float
    {
        return $speed/2.5;
    }

    public function adjustBaseDamage(float $baseDamage): float
    {
        return $baseDamage/2.5;
    }

    public function adjustDamageModifier(float $damageModifier): float
    {
        return $damageModifier/2.5;
    }
}
