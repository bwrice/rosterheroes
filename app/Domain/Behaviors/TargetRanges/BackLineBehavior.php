<?php


namespace App\Domain\Behaviors\TargetRanges;


class BackLineBehavior extends CombatPositionBehavior
{

    public function adjustCombatSpeed(float $speed): float
    {
        return $speed/1.3;
    }

    public function adjustBaseDamage(float $baseDamage): float
    {
        return $baseDamage/1.3;
    }

    public function adjustDamageMultiplier(float $damageModifier): float
    {
        return $damageModifier/1.3;
    }

    public function attackerIconSrc(): string
    {
        return asset('svg/icons/combatPositions/attackers/back-line.svg');
    }

    public function targetIconSrc(): string
    {
        return asset('svg/icons/combatPositions/targets/back-line.svg');
    }

    public function getIconAlt(): string
    {
        return 'Back Line Combat Position';
    }
}
