<?php


namespace App\Domain\Behaviors\EnemyTypes;


use App\Domain\Models\CombatPosition;

class VampireBehavior extends EnemyTypeBehavior
{
    /**
     * @param int $enemyLevel
     * @param string $combatPositionName
     * @return int
     */
    public function getStartingHealth(int $enemyLevel, string $combatPositionName): int
    {
        $base = 300;
        $levelModifier = 11;
        switch ($combatPositionName) {
            case CombatPosition::FRONT_LINE:
                $base += 300;
                $levelModifier += 22;
                break;
            case CombatPosition::BACK_LINE:
                $base += 125;
                $levelModifier += 11;
                break;
        }
        return $base + ($levelModifier * ($enemyLevel ** 1.22));
    }

    /**
     * @param int $enemyLevel
     * @param string $combatPositionName
     * @return int
     */
    public function getProtection(int $enemyLevel, string $combatPositionName): int
    {
        $base = 15;
        $levelModifier = 2;
        switch ($combatPositionName) {
            case CombatPosition::FRONT_LINE:
                $base += 120;
                $levelModifier += 3;
                break;
            case CombatPosition::BACK_LINE:
                $base += 20;
                $levelModifier += 1;
                break;
        }
        return $base + ($levelModifier * $enemyLevel);
    }

    /**
     * @param float $damageProperty
     * @param int $enemyLevel
     * @param string $combatPositionName
     * @return int|float
     */
    protected function adjustDamageProperty(float $damageProperty, int $enemyLevel, string $combatPositionName)
    {
        $modifier = 1 + (.028 * ($enemyLevel ** 1.25));
        switch ($combatPositionName) {
            case CombatPosition::HIGH_GROUND:
                $modifier *= 1.8;
                break;
            case CombatPosition::BACK_LINE:
                $modifier *= 1.5;
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
        return $combatSpeed * (.75 + $enemyLevel/400);
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
        $blockChance = 14 + $enemyLevel/9;
        return min(70, $blockChance);
    }
}
