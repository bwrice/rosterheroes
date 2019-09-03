<?php


namespace App\Domain\Interfaces;


interface AdjustsCombatSpeed
{
    public function adjustBaseSpeed(float $speed): float;
}
