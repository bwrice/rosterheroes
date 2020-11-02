<?php


namespace App\Domain\Behaviors\DamageTypes;


use Illuminate\Support\Collection;

abstract class DamageTypeBehavior
{
    abstract public function getMaxTargetCount(int $tier, ?int $fixedTargetCount);

    abstract public function getDamagePerTarget(int $totalDamage, int $targetsCount);

    abstract public function getInitialBaseDamage(int $tier, ?int $targetsCount): float;

    abstract public function getInitialDamageMultiplier(int $tier, ?int $targetsCount): float;

    abstract public function getInitialCombatSpeed(int $tier, ?int $targetsCount): float;

    abstract public function getResourceCostMagnitude(int $tier, ?int $targetsCount): float;

    abstract public function getResourceCosts(int $tier, ?int $targetsCount): Collection;
}
