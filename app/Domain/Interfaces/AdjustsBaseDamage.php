<?php


namespace App\Domain\Interfaces;


interface AdjustsBaseDamage
{
    public function adjustBaseDamage(float $baseDamage): float;
}
