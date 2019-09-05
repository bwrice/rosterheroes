<?php


namespace App\Domain\Behaviors\TargetRanges;


use App\Domain\Interfaces\AdjustsBaseDamage;
use App\Domain\Interfaces\AdjustsCombatSpeed;
use App\Domain\Interfaces\AdjustsDamageModifier;

interface TargetRangeBehaviorInterface extends AdjustsCombatSpeed, AdjustsBaseDamage, AdjustsDamageModifier
{

}
