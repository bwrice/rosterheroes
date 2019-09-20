<?php


namespace App\Domain\Behaviors\TargetRanges;


abstract class CombatPositionBehavior implements CombatPositionBehaviorInterface
{
    public function getIcon(): array
    {
        return [];
    }
}
