<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\Combatant;
use App\Domain\Combat\CombatAttack;
use App\Domain\Combat\CombatGroup;

class CombatTurnAction
{
    public function execute(CombatGroup $attackers, CombatGroup $defenders, int $moment)
    {
        $attacks = $attackers->getReadyAttacks($moment);
        $attacks->each(function (CombatAttack $combatAttack) use ($defenders, $moment) {
            $combatPositions = $combatAttack->getTargetCombatPositions();
            $targets = $defenders->getTargets($combatPositions, $moment);
            $damage = $combatAttack->getDamage($targets->count());
            $targets->each(function (Combatant $combatant) use ($combatAttack, $damage) {
                // calculate actual damage
                // handle received damage
                // handle damage given
            });
        });
    }
}
