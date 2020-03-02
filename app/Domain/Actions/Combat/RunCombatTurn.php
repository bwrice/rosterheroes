<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Collections\CombatPositionCollection;
use App\Domain\Combat\Combatants\Combatant;
use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\CombatGroups\CombatGroup;

class RunCombatTurn
{
    public function execute(CombatGroup $attackers, CombatGroup $defenders, int $moment, CombatPositionCollection $allCombatPositions, callable $eventCallback)
    {
        $attackers->updateCombatPositions($allCombatPositions);
        $defenders->updateCombatPositions($allCombatPositions);

        $attacks = $attackers->getReadyAttacks($moment);

        $attacks->each(function (CombatAttackInterface $combatAttack) use ($defenders, $moment, $eventCallback) {

            $possibleTargets = $defenders->getPossibleTargets($moment);
            $targets = $combatAttack->getTargets($possibleTargets);

            $startingDamage = $combatAttack->getDamagePerTarget($targets->count());

            $targets->each(function (Combatant $combatant) use ($combatAttack, $startingDamage, $moment, $eventCallback) {

                $block = $combatant->attackBlocked($combatAttack);
                if ($block) {
                    $damageReceived = 0;
                } else {
                    $damageReceived = $combatant->calculateDamageToReceive($startingDamage);
                    $combatant->receiveDamage($damageReceived);
                }
                $eventCallback($damageReceived, $combatAttack, $combatant, $block);
            });
        });
    }
}
