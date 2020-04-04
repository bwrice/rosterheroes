<?php


namespace App\Domain\Behaviors\EnemyTypes;


use App\Domain\Models\CombatPosition;

class GargoyleBehavior extends EnemyTypeBehavior
{
    /**
     * @param int $enemyLevel
     * @param CombatPosition $startingCombatPosition
     * @return int
     */
    public function getStartingHealth(int $enemyLevel, CombatPosition $startingCombatPosition): int
    {
        $base = 600;
        $levelModifier = 12;
        switch ($startingCombatPosition->name) {
            case CombatPosition::FRONT_LINE:
                $base += 400;
                $levelModifier += 25;
                break;
            case CombatPosition::BACK_LINE:
                $base += 150;
                $levelModifier += 12;
                break;
        }
        return $base + ($levelModifier * $enemyLevel);
    }

    /**
     * @param int $enemyLevel
     * @param CombatPosition $startingCombatPosition
     * @return int
     */
    public function getProtection(int $enemyLevel, CombatPosition $startingCombatPosition): int
    {
        $base = 3;
        $levelModifier = .1;
        return (int) ceil($base + ($levelModifier * $enemyLevel));
    }

    /**
     * @param float $damageProperty
     * @param int $enemyLevel
     * @param CombatPosition $startingCombatPosition
     * @return int|float
     */
    protected function adjustDamageProperty(float $damageProperty, int $enemyLevel, CombatPosition $startingCombatPosition)
    {
        $modifier = 1 + ($enemyLevel / 50);
        switch ($startingCombatPosition->name) {
            case CombatPosition::HIGH_GROUND:
                $modifier *= 1.2;
                break;
            case CombatPosition::BACK_LINE:
                $modifier *= 1.1;
                break;
        }
        return $damageProperty * $modifier;
    }

    /**
     * @param float $baseDamage
     * @param int $enemyLevel
     * @param CombatPosition $startingCombatPosition
     * @return float
     */
    public function adjustBaseDamage(float $baseDamage, int $enemyLevel, CombatPosition $startingCombatPosition): float
    {
        return $this->adjustDamageProperty($baseDamage, $enemyLevel, $startingCombatPosition);
    }

    /**
     * @param float $damageMultiplier
     * @param int $enemyLevel
     * @param CombatPosition $startingCombatPosition
     * @return float
     */
    public function adjustDamageMultiplier(float $damageMultiplier, int $enemyLevel, CombatPosition $startingCombatPosition): float
    {
        return $this->adjustDamageProperty($damageMultiplier, $enemyLevel, $startingCombatPosition);
    }

    /**
     * @param float $combatSpeed
     * @param int $enemyLevel
     * @param CombatPosition $startingCombatPosition
     * @return float
     */
    public function adjustCombatSpeed(float $combatSpeed, int $enemyLevel, CombatPosition $startingCombatPosition): float
    {
        return $combatSpeed * (1.1 + $enemyLevel/200);
    }

    /**
     * @param int $enemyLevel
     * @param CombatPosition $startingCombatPosition
     * @return float
     */
    public function getBlockChancePercent(int $enemyLevel, CombatPosition $startingCombatPosition): float
    {
        switch ($startingCombatPosition->name) {
            case CombatPosition::HIGH_GROUND:
            case CombatPosition::BACK_LINE:
                return 0;
        }
        return 15;
    }
}
