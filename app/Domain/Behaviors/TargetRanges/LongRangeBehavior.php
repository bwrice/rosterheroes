<?php


namespace App\Domain\Behaviors\TargetRanges;


class LongRangeBehavior extends TargetRangeBehavior
{

    public function adjustCombatSpeed(float $speed): float
    {
        return $speed/1.75;
    }
}
