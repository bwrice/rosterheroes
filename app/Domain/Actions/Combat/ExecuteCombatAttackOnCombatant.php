<?php


namespace App\Domain\Actions\Combat;


use App\Domain\Combat\Attacks\AdjustDamageForProtection;
use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\Combatants\CombatantInterface;
use App\Domain\Combat\Events\AttackBlocked;
use App\Domain\Combat\Events\AttackDamagesCombatant;
use App\Domain\Combat\Events\AttackKillsCombatant;
use App\Facades\DamageTypeFacade;
use Illuminate\Support\Collection;

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
     * @return Collection
     */
    public function execute(CombatAttackInterface $combatAttack, CombatantInterface $attacker, CombatantInterface $target, int $moment, int $targetsCount)
    {
        $events = collect();
        if ($this->determineIfAttackIsBlocked->execute($target->getBlockChancePercent())) {
            $events->push(new AttackBlocked($combatAttack, $attacker, $target, $moment));
            return $events;
        }

        $initialDamage = DamageTypeFacade::damagePerTarget($combatAttack->getDamageTypeID(), $combatAttack->getDamage(), $targetsCount);
        $damageToReceive = $this->adjustDamageForProtection->execute($initialDamage, $target->getProtection());
        $currentHealth = $target->getCurrentHealth();
        $updatedHealth = max($currentHealth - $damageToReceive, 0);
        $target->updateCurrentHealth($updatedHealth);

        if ($updatedHealth > 0) {
            $events->push(new AttackDamagesCombatant($combatAttack, $attacker, $target, $damageToReceive, $moment));
            return $events;
        }

        // If attack kills the combatant, the actual damage received would be the health before the attack
        $damageTaken = $currentHealth;
        $events->push(new AttackDamagesCombatant($combatAttack, $attacker, $target, $damageTaken, $moment));
        $events->push(new AttackKillsCombatant($combatAttack, $attacker, $target, $currentHealth, $moment));
        return $events;
    }
}
