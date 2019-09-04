<?php


namespace App\Domain\Behaviors\DamageTypes;


class DispersedBehavior extends DamageTypeBehavior
{

    public function adjustCombatSpeed(float $speed): float
    {
        return $speed/2.25;
    }

    public function adjustBaseDamage(float $baseDamage): float
    {
        return $baseDamage * 1.5;
    }
}
