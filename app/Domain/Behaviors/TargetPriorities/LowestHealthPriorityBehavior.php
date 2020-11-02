<?php


namespace App\Domain\Behaviors\TargetPriorities;


use App\Domain\Combat\Combatants\CombatantInterface;

class LowestHealthPriorityBehavior extends TargetPriorityBehavior
{
    protected $sortCombatantsDesc = false;

    public function getCombatantValue(CombatantInterface $combatant)
    {
        return $combatant->getCurrentHealth();
    }
}
