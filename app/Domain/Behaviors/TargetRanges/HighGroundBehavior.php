<?php


namespace App\Domain\Behaviors\TargetRanges;


class HighGroundBehavior extends CombatPositionBehavior
{

    public function adjustCombatSpeed(float $speed): float
    {
        return $speed/1.75;
    }

    public function adjustBaseDamage(float $baseDamage): float
    {
        return $baseDamage/1.75;
    }

    public function adjustDamageMultiplier(float $damageModifier): float
    {
        return $damageModifier/1.75;
    }

    public function attackerIconSrc(): string
    {
        return asset('svg/icons/combatPositions/attackers/high-ground.svg');
    }

    public function targetIconSrc(): string
    {
        return asset('svg/icons/combatPositions/targets/high-ground.svg');
    }

    public function getIconAlt(): string
    {
        return 'High Ground Combat Position';
    }
}
