<?php


namespace App\Domain\Behaviors\TargetRanges;


class MeleeRangeBehavior extends TargetRangeBehavior
{

    public function adjustCombatSpeed(float $speed): float
    {
        return $speed;
    }
}
