<?php


namespace App\Domain\Behaviors\TargetPriorities;


use App\Domain\Combat\Combatants\Combatant;

class HighestThreatPriorityBehavior extends TargetPriorityBehavior
{
    public function getCombatantValue(Combatant $combatant)
    {
        return $combatant->getThreatLevel();
    }
}
