<?php


namespace App\Domain\Behaviors\EnemyTypes;


use App\Domain\Models\CombatPosition;

class WitchBehavior extends EnemyTypeBehavior
{
    /**
     * @param int $enemyLevel
     * @param string $combatPositionName
     * @return int
     */
    public function getStartingHealth(int $enemyLevel, string $combatPositionName): int
    {
        $base = 250;
        $levelModifier = 10;
        switch ($combatPositionName) {
            case CombatPosition::FRONT_LINE:
                $base += 450;
                $levelModifier += 12;
                break;
            case CombatPosition::BACK_LINE:
                $base += 100;
                $levelModifier += 5;
                break;
        }
        return $base + ($levelModifier * ($enemyLevel ** 1.16));
    }

    /**
     * @param int $enemyLevel
     * @param string $combatPositionName
     * @return int
     */
    public function getProtection(int $enemyLevel, string $combatPositionName): int
    {
        $base = 5;
        $levelModifier = 1;
        switch ($combatPositionName) {
            case CombatPosition::FRONT_LINE:
                $base += 100;
                $levelModifier += 2;
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
        $modifier = 1 + (0.32 * ($enemyLevel ** 1.27));
        switch ($startingCombatPosition->name) {
            case CombatPosition::HIGH_GROUND:
            case CombatPosition::BACK_LINE:
                $modifier *= 1.5;
                break;
            case CombatPosition::FRONT_LINE:
                $modifier *= .8;
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
        return $combatSpeed * (1.15 + $enemyLevel/125);
    }

    /**
     * @param int $enemyLevel
     * @param string $combatPositionName
     * @return float
     */
    public function getBlockChancePercent(int $enemyLevel, string $combatPositionName): float
    {
        switch ($combatPositionName) {
            case CombatPosition::FRONT_LINE:
                return 15;
        }
        return 0;
    }
}
