<?php


namespace App\Domain\Behaviors\DamageTypes;


class FixedTargetBehavior extends DamageTypeBehavior
{

    public function getCombatSpeedBonus(int $attackGrade, float $combatSpeedRating, int $fixedTargetCount)
    {
        return $speed;
    }

    public function getBaseDamageBonus(int $attackGrade, float $baseDamageRating, int $fixedTargetCount)
    {
        return $baseDamage;
    }

    public function getDamageMultiplierBonus(int $attackGrade, float $damageMultiplierRating, int $fixedTargetCount)
    {
        return $damageModifier;
    }
}
