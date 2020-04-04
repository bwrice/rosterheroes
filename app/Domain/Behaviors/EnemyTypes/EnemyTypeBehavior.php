<?php


namespace App\Domain\Behaviors\EnemyTypes;

use App\Domain\Models\CombatPosition;


abstract class EnemyTypeBehavior
{
    protected $healthModifierBonus = 0;
    protected $protectionModifierBonus = 0;
    protected $blockModifierBonus = 0;
    protected $baseDamageModifierBonus = 0;
    protected $damageMultiplierModifierBonus = 0;
    protected $combatSpeedModifierBonus = 0;

    /**
     * @param int $enemyLevel
     * @param CombatPosition $startingCombatPosition
     * @return int
     */
    abstract public function getStartingHealth(int $enemyLevel, CombatPosition $startingCombatPosition): int;

    /**
     * @param int $enemyLevel
     * @param CombatPosition $startingCombatPosition
     * @return int
     */
    abstract public function getProtection(int $enemyLevel, CombatPosition $startingCombatPosition): int;

    /**
     * @param float $baseDamage
     * @param int $enemyLevel
     * @param CombatPosition $startingCombatPosition
     * @return int
     */
    abstract public function adjustBaseDamage(float $baseDamage, int $enemyLevel, CombatPosition $startingCombatPosition): int;

    /**
     * @param float $damageMultiplier
     * @param int $enemyLevel
     * @param CombatPosition $startingCombatPosition
     * @return float
     */
    abstract public function adjustDamageMultiplier(float $damageMultiplier, int $enemyLevel, CombatPosition $startingCombatPosition): float;

    /**
     * @param float $combatSpeed
     * @param int $enemyLevel
     * @param CombatPosition $startingCombatPosition
     * @return float
     */
    abstract public function adjustCombatSpeed(float $combatSpeed, int $enemyLevel, CombatPosition $startingCombatPosition): float;

    /**
     * @param int $enemyLevel
     * @param CombatPosition $startingCombatPosition
     * @return float
     */
    abstract public function getBlockChancePercent(int $enemyLevel, CombatPosition $startingCombatPosition): float;
}
