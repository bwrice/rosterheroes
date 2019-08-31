<?php


namespace App\Domain\Interfaces;


interface AdjustsCombatSpeed
{
    public function adjustCombatSpeed(float $speed): float;
}
