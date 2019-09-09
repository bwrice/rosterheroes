<?php


namespace App\Domain\Interfaces;


interface AdjustsDamageModifier
{
    public function adjustDamageMultiplier(float $damageModifier): float;
}
