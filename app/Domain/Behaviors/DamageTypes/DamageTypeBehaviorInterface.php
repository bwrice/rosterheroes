<?php


namespace App\Domain\Behaviors\DamageTypes;


use App\Domain\Interfaces\AdjustsBaseDamage;
use App\Domain\Interfaces\AdjustsCombatSpeed;
use App\Domain\Interfaces\AdjustsDamageModifier;

interface DamageTypeBehaviorInterface extends AdjustsCombatSpeed, AdjustsBaseDamage, AdjustsDamageModifier
{

}
