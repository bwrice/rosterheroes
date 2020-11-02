<?php


namespace App\Domain\Behaviors\EnemyTypes;


use App\Domain\Models\CombatPosition;

class GiantBehavior extends EnemyTypeBehavior
{
    /**
     * @param int $enemyLevel
     * @param string $combatPositionName
     * @return int
     */
    public function getStartingHealth(int $enemyLevel, string $combatPositionName): int
    {
        $base = 1000;
        $levelModifier = 10;
        switch ($combatPositionName) {
            case CombatPosition::FRONT_LINE:
                $base += 600;
                $levelModifier += 32;
                break;
            case CombatPosition::BACK_LINE:
                $base += 150;
                $levelModifier += 20;
                break;
        }
        return $base + ($levelModifier * ($enemyLevel ** 1.35));
    }

    /**
     * @param int $enemyLevel
     * @param string $combatPositionName
     * @return int
     */
    public function getProtection(int $enemyLevel, string $combatPositionName): int
    {
        $base = 20;
        $levelModifier = 1.75;
        switch ($combatPositionName) {
            case CombatPosition::FRONT_LINE:
                $base += 100;
                $levelModifier += 2;
                break;
            case CombatPosition::BACK_LINE:
                $base += 50;
                $levelModifier += 1;
                break;
        }
        return (int) ceil($base + ($levelModifier * $enemyLevel));
    }

    /**
     * @param float $damageProperty
     * @param int $enemyLevel
     * @param string $combatPositionName
     * @return int|float
     */
    protected function adjustDamageProperty(float $damageProperty, int $enemyLevel, string $combatPositionName)
    {
        $modifier = 1 + (.19 * ($enemyLevel ** 1.16));
        switch ($combatPositionName) {
            case CombatPosition::HIGH_GROUND:
                $modifier *= 1.25;
                break;
            case CombatPosition::BACK_LINE:
                $modifier *= 1.15;
                break;
        }
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
        return $this->adjustDamageProperty($baseDamage, $enemyLevel, $combatPositionName);
    }

    /**
     * @param float $damageMultiplier
     * @param int $enemyLevel
     * @param string $combatPositionName
     * @return float
     */
    public function adjustDamageMultiplier(float $damageMultiplier, int $enemyLevel, string $combatPositionName): float
    {
        return $this->adjustDamageProperty($damageMultiplier, $enemyLevel, $combatPositionName);
    }

    /**
     * @param float $combatSpeed
     * @param int $enemyLevel
     * @param string $combatPositionName
     * @return float
     */
    public function adjustCombatSpeed(float $combatSpeed, int $enemyLevel, string $combatPositionName): float
    {
        return $combatSpeed * (.4 + $enemyLevel/500);
    }

    /**
     * @param int $enemyLevel
     * @param string $combatPositionName
     * @return float
     */
    public function getBlockChancePercent(int $enemyLevel, string $combatPositionName): float
    {
        switch ($combatPositionName) {
            case CombatPosition::HIGH_GROUND:
            case CombatPosition::BACK_LINE:
                return 0;
        }
        $blockChance = 24 + $enemyLevel/3.8;
        return min(70, $blockChance);
    }
}
