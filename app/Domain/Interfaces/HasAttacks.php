<?php


namespace App\Domain\Interfaces;


use Illuminate\Support\Collection;

interface HasAttacks
{
    public function adjustBaseDamage(float $baseDamage): float;

    public function adjustCombatSpeed(float $speed): float;

    public function adjustDamageMultiplier(float $damageMultiplier): float;

    public function adjustResourceCostAmount(float $amount): int;

    public function adjustResourceCostPercent(float $amount): float;

    public function adjustResourceCosts(Collection $resourceCosts): Collection;
}
