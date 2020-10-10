<?php


namespace App\Domain\Behaviors\TargetPriorities;


use App\Domain\Combat\Combatants\CombatantInterface;

class HighestThreatPriorityBehavior extends TargetPriorityBehavior
{
    public function getCombatantValue(CombatantInterface $combatant)
    {
        return $combatant->getThreatLevel();
    }
}
