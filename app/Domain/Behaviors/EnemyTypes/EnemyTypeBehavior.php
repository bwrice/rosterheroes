<?php


namespace App\Domain\Behaviors\EnemyTypes;


abstract class EnemyTypeBehavior
{

    /**
     * @param int $enemyLevel
     * @param string $combatPositionName
     * @return int
     */
    abstract public function getStartingHealth(int $enemyLevel, string $combatPositionName): int;

    /**
     * @param int $enemyLevel
     * @param string $combatPositionName
     * @return int
     */
    abstract public function getProtection(int $enemyLevel, string $combatPositionName): int;

    /**
     * @param float $baseDamage
     * @param int $enemyLevel
     * @param string $combatPositionName
     * @return float
     */
    abstract public function adjustBaseDamage(float $baseDamage, int $enemyLevel, string $combatPositionName): float;

    /**
     * @param float $damageMultiplier
     * @param int $enemyLevel
     * @param string $combatPositionName
     * @return float
     */
    abstract public function adjustDamageMultiplier(float $damageMultiplier, int $enemyLevel, string $combatPositionName): float;

    /**
     * @param float $combatSpeed
     * @param int $enemyLevel
     * @param string $combatPositionName
     * @return float
     */
    abstract public function adjustCombatSpeed(float $combatSpeed, int $enemyLevel, string $combatPositionName): float;

    /**
     * @param int $enemyLevel
     * @param string $combatPositionName
     * @return float
     */
    abstract public function getBlockChancePercent(int $enemyLevel, string $combatPositionName): float;
}
