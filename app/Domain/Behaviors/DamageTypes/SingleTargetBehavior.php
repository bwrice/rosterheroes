<?php


namespace App\Domain\Behaviors\DamageTypes;


class SingleTargetBehavior extends DamageTypeBehavior
{

    public function adjustBaseSpeed(float $speed): float
    {
        return $speed;
    }

    public function adjustBaseDamage(float $baseDamage): float
    {
        return $baseDamage;
    }
}
