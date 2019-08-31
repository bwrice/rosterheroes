<?php


namespace App\Domain\Behaviors\DamageTypes;


class AreaOfEffectBehavior extends DamageTypeBehavior
{

    public function adjustCombatSpeed(float $speed): float
    {
        return $speed/2.5;
    }
}
