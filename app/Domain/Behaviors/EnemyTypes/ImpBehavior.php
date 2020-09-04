<?php


namespace App\Domain\Behaviors\EnemyTypes;


use App\Domain\Models\CombatPosition;

class ImpBehavior extends EnemyTypeBehavior
{
    /**
     * @param int $enemyLevel
     * @param CombatPosition $startingCombatPosition
     * @return int
     */
    public function getStartingHealth(int $enemyLevel, CombatPosition $startingCombatPosition): int
    {
        $base = 120;
        $levelModifier = 14;
        return $base + ($levelModifier * ($enemyLevel ** 1.15));
    }

    /**
     * @param int $enemyLevel
     * @param CombatPosition $startingCombatPosition
     * @return int
     */
    public function getProtection(int $enemyLevel, CombatPosition $startingCombatPosition): int
    {
        $base = 0;
        $levelModifier = .05;
        return (int) ceil($base + ($levelModifier * $enemyLevel));
    }

    /**
     * @param float $damageProperty
     * @param int $enemyLevel
     * @return int|float
     */
    protected function adjustDamageProperty(float $damageProperty, int $enemyLevel)
    {
        return $damageProperty * (1 + (.045 * ($enemyLevel ** 1.28)));
    }

    /**
     * @param float $baseDamage
     * @param int $enemyLevel
     * @param CombatPosition $startingCombatPosition
     * @return float
     */
    public function adjustBaseDamage(float $baseDamage, int $enemyLevel, CombatPosition $startingCombatPosition): float
    {
        return $this->adjustDamageProperty($baseDamage, $enemyLevel);
    }

    /**
     * @param float $damageMultiplier
     * @param int $enemyLevel
     * @param CombatPosition $startingCombatPosition
     * @return float
     */
    public function adjustDamageMultiplier(float $damageMultiplier, int $enemyLevel, CombatPosition $startingCombatPosition): float
    {
        return $this->adjustDamageProperty($damageMultiplier, $enemyLevel);
    }

    /**
     * @param float $combatSpeed
     * @param int $enemyLevel
     * @param CombatPosition $startingCombatPosition
     * @return float
     */
    public function adjustCombatSpeed(float $combatSpeed, int $enemyLevel, CombatPosition $startingCombatPosition): float
    {
        return $combatSpeed * (1.5 + $enemyLevel/225);
    }

    /**
     * @param int $enemyLevel
     * @param CombatPosition $startingCombatPosition
     * @return float
     */
    public function getBlockChancePercent(int $enemyLevel, CombatPosition $startingCombatPosition): float
    {
        return 0;
    }
}
