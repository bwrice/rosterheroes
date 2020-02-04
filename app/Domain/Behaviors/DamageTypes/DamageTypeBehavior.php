<?php


namespace App\Domain\Behaviors\DamageTypes;


abstract class DamageTypeBehavior
{
    abstract public function getBaseDamageBonus(?int $fixedTargetCount);

    abstract public function getCombatSpeedBonus(?int $fixedTargetCount);

    abstract public function getDamageMultiplierBonus(?int $fixedTargetCount);

    abstract public function getMaxTargetCount(int $grade, ?int $fixedTargetCount);
}
