<?php


namespace App\Domain\Behaviors\TargetPriorities;


use App\Domain\Combat\Combatants\CombatantInterface;

class AnyPriorityBehavior extends TargetPriorityBehavior
{

    public function getCombatantValue(CombatantInterface $combatant)
    {
        // return same value for all combatants
        return 1;
    }
}
