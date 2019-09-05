<?php


namespace App\Domain\Interfaces;


interface AdjustsDamageModifier
{
    public function adjustDamageModifier(float $damageModifier): float;
}
