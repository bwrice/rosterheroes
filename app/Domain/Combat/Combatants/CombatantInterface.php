<?php


namespace App\Domain\Combat\Combatants;


use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Models\CombatPosition;

interface CombatantInterface
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
     * @return int
     */
    public function getCurrentHealth(): int;

    /**
     * @return int
     */
    public function getProtection(): int;

    /**
     * @return float
     */
    public function getBlockChancePercent(): float;

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
