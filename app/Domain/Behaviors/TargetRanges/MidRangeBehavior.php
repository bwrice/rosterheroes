<?php


namespace App\Domain\Behaviors\TargetRanges;


class MidRangeBehavior extends TargetRangeBehavior
{

    public function adjustBaseSpeed(float $speed): float
    {
        return $speed/1.3;
    }

    public function adjustBaseDamage(float $baseDamage): float
    {
        return $baseDamage/1.3;
    }
}
