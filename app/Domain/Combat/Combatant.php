<?php


namespace App\Domain\Combat;


use App\Domain\Models\CombatPosition;

interface Combatant
{
    /**
     * @param int $initialDamage
     * @return int
     */
    public function calculateDamageToReceive(int $initialDamage): int;

    /**
     * @param int $damage
     * @return mixed
     */
    public function receiveDamage(int $damage);

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
    public function hasCombatPosition(CombatPosition $combatPositionToCompare): bool;
}
