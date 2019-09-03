<?php


namespace App\Domain\Behaviors\DamageTypes;


use App\Domain\Interfaces\AdjustsBaseDamage;
use App\Domain\Interfaces\AdjustsCombatSpeed;

interface DamageTypeBehaviorInterface extends AdjustsCombatSpeed, AdjustsBaseDamage
{

}
