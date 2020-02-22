<?php


namespace App\Domain\Behaviors\TargetPriorities;


use App\Domain\Combat\Combatants\Combatant;

class AnyPriorityBehavior extends TargetPriorityBehavior
{

    public function getCombatantValue(Combatant $combatant)
    {
        // return same value for all combatants
        return 1;
    }
}
