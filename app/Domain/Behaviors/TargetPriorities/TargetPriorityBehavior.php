<?php


namespace App\Domain\Behaviors\TargetPriorities;


use App\Domain\Combat\Combatants\CombatantInterface;

abstract class TargetPriorityBehavior
{
    protected $sortCombatantsDesc = true;

    abstract public function getCombatantValue(CombatantInterface $combatant);

    public function sortCombatantsDescending()
    {
        return $this->sortCombatantsDesc;
    }
}
