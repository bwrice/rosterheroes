<?php


namespace App\Domain\Behaviors\TargetRanges;


interface TargetRangeBehaviorInterface
{
    public function adjustCombatSpeed(float $speed): float;
}
