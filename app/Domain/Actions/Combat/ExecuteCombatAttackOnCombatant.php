<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\Combatants\Combatant;
use App\Domain\Combat\Events\AttackBlocked;
use App\Domain\Combat\Events\CombatEvent;

class ExecuteCombatAttackOnCombatant
{
    /**
     * @param CombatAttackInterface $combatAttack
     * @param Combatant $combatant
     * @param int $moment
     * @return CombatEvent
     */
    public function execute(CombatAttackInterface $combatAttack, Combatant $combatant, int $moment)
    {
        if ($combatant->attackBlocked($combatAttack)) {
            return new AttackBlocked($combatAttack, $combatant, $moment);
        }
    }
}
