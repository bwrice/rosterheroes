<?php


namespace App\Domain\Behaviors\DamageTypes;


abstract class DamageTypeBehavior
{
    abstract public function getMaxTargetCount(int $grade, ?int $fixedTargetCount);

    abstract public function getDamagePerTarget(int $damage, int $targetsCount);

    abstract public function getInitialBaseDamage(int $tier, ?int $targetsCount): float;

    abstract public function getInitialDamageMultiplier(int $tier, ?int $targetsCount): float;

    abstract public function getInitialCombatSpeed(int $tier, ?int $targetsCount): float;

    abstract public function getResourceCostMagnitude(int $tier, ?int $targetsCount): float;
}
