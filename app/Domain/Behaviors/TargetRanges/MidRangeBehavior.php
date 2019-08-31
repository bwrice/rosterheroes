<?php


namespace App\Domain\Behaviors\TargetRanges;


class MidRangeBehavior extends TargetRangeBehavior
{

    public function adjustCombatSpeed(float $speed): float
    {
        return $speed/1.3;
    }
}
