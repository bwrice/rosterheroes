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

    public function attackerIconSrc(): string
    {
        return asset('svg/icons/combatPositions/attackers/front-line.svg');
    }

    public function targetIconSrc(): string
    {
        return asset('svg/icons/combatPositions/targets/front-line.svg');
    }

    public function getIconAlt(): string
    {
        return 'Front Line Combat Position';
    }
}
