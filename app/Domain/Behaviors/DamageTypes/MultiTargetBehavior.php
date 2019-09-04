<?php


namespace App\Domain\Behaviors\DamageTypes;


class MultiTargetBehavior extends DamageTypeBehavior
{

    public function adjustCombatSpeed(float $speed): float
    {
        return $speed/1.8;
    }

    public function adjustBaseDamage(float $baseDamage): float
    {
        return $baseDamage/1.8;
    }
}
