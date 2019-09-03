<?php


namespace App\Domain\Behaviors\DamageTypes;


class AreaOfEffectBehavior extends DamageTypeBehavior
{

    public function adjustBaseSpeed(float $speed): float
    {
        return $speed/2.5;
    }

    public function adjustBaseDamage(float $baseDamage): float
    {
        return $baseDamage/2.5;
    }
}
