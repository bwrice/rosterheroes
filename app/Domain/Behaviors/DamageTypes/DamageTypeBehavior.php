<?php


namespace App\Domain\Behaviors\DamageTypes;


abstract class DamageTypeBehavior
{
    abstract public function getMaxTargetCount(int $grade, ?int $fixedTargetCount);

    abstract public function getDamagePerTarget(int $damage, int $targetsCount);
}
