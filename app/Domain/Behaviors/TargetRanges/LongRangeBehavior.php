<?php


namespace App\Domain\Behaviors\TargetRanges;


class LongRangeBehavior extends TargetRangeBehavior
{

    public function adjustBaseSpeed(float $speed): float
    {
        return $speed/1.75;
    }

    public function adjustBaseDamage(float $baseDamage): float
    {
        return $baseDamage/1.75;
    }
}
