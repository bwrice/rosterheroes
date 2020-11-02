<?php


namespace App\Domain\Behaviors\EnemyTypes;


use App\Domain\Models\CombatPosition;

class GolemBehavior extends EnemyTypeBehavior
{
    /**
     * @param int $enemyLevel
     * @param string $combatPositionName
     * @return int
     */
    public function getStartingHealth(int $enemyLevel, string $combatPositionName): int
    {
        $base = 1800;
        return $base + (100 * ($enemyLevel**1.36));
    }

    /**
     * @param int $enemyLevel
     * @param string $combatPositionName
     * @return int
     */
    public function getProtection(int $enemyLevel, string $combatPositionName): int
    {
        $base = 250;
        $levelModifier = 5;

        return $base + ($levelModifier * $enemyLevel);
    }

    /**
     * @param float $damageProperty
     * @param int $enemyLevel
     * @return int|float
     */
    protected function adjustDamageProperty(float $damageProperty, int $enemyLevel)
    {
        $modifier = 1 + (.022 * ($enemyLevel ** 1.18));
        return $damageProperty * $modifier;
    }

    /**
     * @param float $baseDamage
     * @param int $enemyLevel
     * @param string $combatPositionName
     * @return float
     */
    public function adjustBaseDamage(float $baseDamage, int $enemyLevel, string $combatPositionName): float
    {
        return $this->adjustDamageProperty($baseDamage, $enemyLevel);
    }

    /**
     * @param float $damageMultiplier
     * @param int $enemyLevel
     * @param string $combatPositionName
     * @return float
     */
    public function adjustDamageMultiplier(float $damageMultiplier, int $enemyLevel, string $combatPositionName): float
    {
        return $this->adjustDamageProperty($damageMultiplier, $enemyLevel);
    }

    /**
     * @param float $combatSpeed
     * @param int $enemyLevel
     * @param string $combatPositionName
     * @return float
     */
    public function adjustCombatSpeed(float $combatSpeed, int $enemyLevel, string $combatPositionName): float
    {
        return $combatSpeed * (.17 + $enemyLevel/1000);
    }

    /**
     * @param int $enemyLevel
     * @param string $combatPositionName
     * @return float
     */
    public function getBlockChancePercent(int $enemyLevel, string $combatPositionName): float
    {
        $blockChance = 4 + $enemyLevel/40;
        return min(70, $blockChance);
    }
}
