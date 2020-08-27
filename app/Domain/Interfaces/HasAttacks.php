<?php


namespace App\Domain\Interfaces;


use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Behaviors\DamageTypes\DamageTypeBehavior;
use App\Domain\Models\DamageType;

interface HasAttacks
{
    public function adjustBaseDamage(float $baseDamage): float;

    public function adjustCombatSpeed(float $speed): float;

    public function adjustDamageMultiplier(float $damageMultiplier): float;

    public function adjustResourceCostAmount(float $amount): int;

    public function adjustResourceCostPercent(float $amount): float;

    public function adjustResourceCosts(ResourceCostsCollection $resourceCosts): ResourceCostsCollection;
}
