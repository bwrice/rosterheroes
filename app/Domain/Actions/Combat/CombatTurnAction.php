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
            $targets = $combatAttack->getTargets($possibleTargets);

            $startingDamage = $combatAttack->getDamagePerTarget($targets->count());

            $possibleTargets->each(function (Combatant $combatant) use ($combatAttack, $startingDamage, $moment, $eventCallback) {

                $block = $combatant->attackBlocked($combatAttack);
                if ($block) {
                    $damageReceived = 0;
                } else {
                    $damageReceived = $combatant->calculateDamageToReceive($startingDamage);
                    $combatant->receiveDamage($damageReceived);
                }
                $eventCallback($damageReceived, $combatAttack, $combatant, $moment, $block);
            });
        });
    }
}
