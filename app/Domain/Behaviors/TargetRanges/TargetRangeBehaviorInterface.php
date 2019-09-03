<?php


namespace App\Domain\Behaviors\TargetRanges;


use App\Domain\Interfaces\AdjustsBaseDamage;
use App\Domain\Interfaces\AdjustsCombatSpeed;

interface TargetRangeBehaviorInterface extends AdjustsCombatSpeed, AdjustsBaseDamage
{

}
