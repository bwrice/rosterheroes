<?php


namespace App\Domain\Behaviors\TargetRanges;


class MeleeRangeBehavior extends TargetRangeBehavior
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
