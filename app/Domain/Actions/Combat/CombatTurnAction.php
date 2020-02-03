<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\Combatant;
use App\Domain\Combat\CombatAttack;
use App\Domain\Combat\CombatGroup;

class CombatTurnAction
{
    public function execute(CombatGroup $attackers, CombatGroup $defenders, int $moment, callable $eventCallback)
    {
        $attacks = $attackers->getReadyAttacks($moment);
        $attacks->each(function (CombatAttack $combatAttack) use ($defenders, $moment, $eventCallback) {

            $possibleTargets = $defenders->getPossibleTargets($moment);
            $filteredTargets = $possibleTargets->filterByCombatPositions($combatAttack->getTargetCombatPositions());
            $sortedTargets = $filteredTargets->sortByTargetPriority($combatAttack->getTargetPriority());
            $targets = $sortedTargets->take($combatAttack->getMaxTargets());

            $damagePerTarget = $combatAttack->getDamagePerTarget($targets->count());

            $possibleTargets->each(function (Combatant $combatant) use ($combatAttack, $damagePerTarget, $eventCallback) {
                $damageReceived = $combatant->receiveDamage($damagePerTarget);
                $eventCallback($damageReceived, $combatAttack, $combatant);
            });
        });
    }
}
