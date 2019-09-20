<?php


namespace App\Domain\Behaviors\TargetRanges;


class FrontLineBehavior extends CombatPositionBehavior
{

    public function adjustCombatSpeed(float $speed): float
    {
        return $speed;
    }

    public function adjustBaseDamage(float $baseDamage): float
    {
        return $baseDamage;
    }

    public function adjustDamageMultiplier(float $damageModifier): float
    {
        return $damageModifier;
    }

    public function attackerIcon(): string
    {
        return asset('svg/icons/combatPositions/attackers/front-line.svg');
    }

    public function targetIcon(): string
    {
        return asset('svg/icons/combatPositions/targets/front-line.svg');
    }
}
