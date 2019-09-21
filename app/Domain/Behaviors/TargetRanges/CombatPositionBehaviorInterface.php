<?php


namespace App\Domain\Behaviors\TargetRanges;


use App\Domain\Interfaces\AdjustsBaseDamage;
use App\Domain\Interfaces\AdjustsCombatSpeed;
use App\Domain\Interfaces\AdjustsDamageModifier;

interface CombatPositionBehaviorInterface extends AdjustsCombatSpeed, AdjustsBaseDamage, AdjustsDamageModifier
{
    public function getIcon($attacker = true): array;
}
