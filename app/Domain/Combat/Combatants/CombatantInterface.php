<?php


namespace App\Domain\Combat\Combatants;


use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Models\CombatPosition;

interface Combatant
{
    /**
     * @param int $initialDamage
     * @return int
     */
    public function calculateDamageToReceive(int $initialDamage): int;

    /**
     * @param int $amount
     * @return mixed
     */
    public function updateCurrentHealth(int $amount);

    /**
     * @param CombatAttackInterface $combatAttack
     * @return mixed
     */
    public function attackBlocked(CombatAttackInterface $combatAttack);

    /**
     * @return int
     */
    public function getCurrentHealth(): int;

    /**
     * @return float
     */
    public function getThreatLevel(): float;

    /**
     * @param CombatPosition $combatPositionToCompare
     * @return bool
     */
    public function hasCombatPosition(int $combatPositionID): bool;
}
