<?php


namespace App\Domain\Behaviors\TargetPriorities;


use App\Domain\Combat\Combatants\Combatant;

abstract class TargetPriorityBehavior
{
    protected $sortCombatantsDesc = true;

    abstract public function getCombatantValue(Combatant $combatant);

    public function sortCombatantsDescending()
    {
        return $this->sortCombatantsDesc;
    }
}
