<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\Combatants\Combatant;
use App\Domain\Combat\Events\AttackBlocked;
use App\Domain\Combat\Events\AttackDamagesCombatant;
use App\Domain\Combat\Events\AttackKillsCombatant;
use App\Domain\Combat\Events\CombatEvent;

class ExecuteCombatAttackOnCombatant
{
    /**
     * @param CombatAttackInterface $combatAttack
     * @param Combatant $combatant
     * @param int $moment
     * @param int $targetsCount
     * @return CombatEvent
     */
    public function execute(CombatAttackInterface $combatAttack, Combatant $combatant, int $moment, int $targetsCount)
    {
        if ($combatant->attackBlocked($combatAttack)) {
            return new AttackBlocked($combatAttack, $combatant, $moment);
        }

        $initialDamage = $combatAttack->getDamagePerTarget($targetsCount);
        $damageToReceive = $combatant->calculateDamageToReceive($initialDamage);
        $currentHealth = $combatant->getCurrentHealth();
        $updatedHealth = max($currentHealth - $damageToReceive, 0);
        $combatant->updateCurrentHealth($updatedHealth);

        if ($updatedHealth > 0) {
            return new AttackDamagesCombatant($combatAttack, $combatant, $damageToReceive, $moment);
        }

        return new AttackKillsCombatant($combatAttack, $combatant, $damageToReceive, $currentHealth, $moment);
    }
}
