<?php


namespace App\Domain\Behaviors\TargetPriorities;


use App\Domain\Combat\Combatant;

class LowestHealthPriorityBehavior extends TargetPriorityBehavior
{
    protected $sortCombatantsDesc = false;

    public function getCombatantValue(Combatant $combatant)
    {
        return $combatant->getCurrentHealth();
    }
}
