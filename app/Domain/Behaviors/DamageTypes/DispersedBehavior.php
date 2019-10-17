<?php


namespace App\Domain\Behaviors\DamageTypes;


class DispersedBehavior extends DamageTypeBehavior
{

    public function getCombatSpeedBonus(int $fixedTargetCount)
    {
        return .25;
    }

    public function getBaseDamageBonus(int $fixedTargetCount)
    {
        return 1.5;
    }

    public function getDamageMultiplierBonus(int $fixedTargetCount)
    {
        return 1.5;
    }
}
