<?php


namespace App\Domain\Behaviors\EnemyTypes;


use App\Domain\Models\CombatPosition;

class GargoyleBehavior extends EnemyTypeBehavior
{
    /**
     * @param int $enemyLevel
     * @param string $combatPositionName
     * @return int
     */
    public function getStartingHealth(int $enemyLevel, string $combatPositionName): int
    {
        $base = 800;
        $levelModifier = 36;
        return $base + ($levelModifier * ($enemyLevel ** 1.27));
    }

    /**
     * @param int $enemyLevel
     * @param string $combatPositionName
     * @return int
     */
    public function getProtection(int $enemyLevel, string $combatPositionName): int
    {
        $base = 5;
        $levelModifier = .1;
        return (int) ceil($base + ($levelModifier * $enemyLevel));
    }

    /**
     * @param float $damageProperty
     * @param int $enemyLevel
     * @return int|float
     */
    protected function adjustDamageProperty(float $damageProperty, int $enemyLevel)
    {
        $modifier = 1 + (0.03 * $enemyLevel ** 1.21);
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
        return $combatSpeed * (1.19 + $enemyLevel/200);
    }

    /**
     * @param int $enemyLevel
     * @param string $combatPositionName
     * @return float
     */
    public function getBlockChancePercent(int $enemyLevel, string $combatPositionName): float
    {
        return 8 + $enemyLevel/30;
    }
}
