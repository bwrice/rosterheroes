<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\Attacks\AdjustDamageForProtection;
use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\Combatants\CombatantInterface;
use App\Domain\Combat\Events\AttackBlocked;
use App\Domain\Combat\Events\AttackDamagesCombatant;
use App\Domain\Combat\Events\AttackKillsCombatant;
use App\Domain\Combat\Events\CombatEvent;
use App\Facades\DamageTypeFacade;

class ExecuteCombatAttackOnCombatant
{
    protected DetermineIfAttackIsBlocked $determineIfAttackIsBlocked;
    protected AdjustDamageForProtection $adjustDamageForProtection;

    public function __construct(
        DetermineIfAttackIsBlocked $determineIfAttackIsBlocked,
        AdjustDamageForProtection $adjustDamageForProtection)
    {
        $this->determineIfAttackIsBlocked = $determineIfAttackIsBlocked;
        $this->adjustDamageForProtection = $adjustDamageForProtection;
    }

    /**
     * @param CombatAttackInterface $combatAttack
     * @param CombatantInterface $attacker
     * @param CombatantInterface $target
     * @param int $moment
     * @param int $targetsCount
     * @return CombatEvent
     */
    public function execute(CombatAttackInterface $combatAttack, CombatantInterface $attacker, CombatantInterface $target, int $moment, int $targetsCount)
    {
        if ($this->determineIfAttackIsBlocked->execute($target->getBlockChancePercent())) {
            return new AttackBlocked($combatAttack, $attacker, $target, $moment);
        }

        $initialDamage = DamageTypeFacade::damagePerTarget($combatAttack->getDamageTypeID(), $combatAttack->getDamage(), $targetsCount);
        $damageToReceive = $this->adjustDamageForProtection->execute($initialDamage, $target->getProtection());
        $currentHealth = $target->getCurrentHealth();
        $updatedHealth = max($currentHealth - $damageToReceive, 0);
        $target->updateCurrentHealth($updatedHealth);

        if ($updatedHealth > 0) {
            return new AttackDamagesCombatant($combatAttack, $attacker, $target, $damageToReceive, $moment);
        }

        return new AttackKillsCombatant($combatAttack, $attacker, $target, $damageToReceive, $currentHealth, $moment);
    }
}
