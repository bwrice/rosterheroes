<?php


namespace App\Domain\Behaviors\EnemyTypes;


use App\Domain\Models\CombatPosition;

class UndeadBehavior extends EnemyTypeBehavior
{
    /**
     * @param int $enemyLevel
     * @param CombatPosition $startingCombatPosition
     * @return int
     */
    public function getStartingHealth(int $enemyLevel, CombatPosition $startingCombatPosition): int
    {
        $base = 350;
        $levelModifier = 20;
        switch ($startingCombatPosition->name) {
            case CombatPosition::FRONT_LINE:
                $base += 300;
                $levelModifier += 24;
                break;
            case CombatPosition::BACK_LINE:
                $base += 150;
                $levelModifier += 10;
                break;
        }
        return $base + ($levelModifier * ($enemyLevel ** 1.16));
    }

    /**
     * @param int $enemyLevel
     * @param CombatPosition $startingCombatPosition
     * @return int
     */
    public function getProtection(int $enemyLevel, CombatPosition $startingCombatPosition): int
    {
        $base = 25;
        $levelModifier = 2;
        switch ($startingCombatPosition->name) {
            case CombatPosition::FRONT_LINE:
                $base += 150;
                $levelModifier += 3;
                break;
            case CombatPosition::BACK_LINE:
                $base += 50;
                $levelModifier += 1;
                break;
        }
        return $base + ($levelModifier * $enemyLevel);
    }

    /**
     * @param float $damageProperty
     * @param int $enemyLevel
     * @param CombatPosition $startingCombatPosition
     * @return int|float
     */
    protected function adjustDamageProperty(float $damageProperty, int $enemyLevel, CombatPosition $startingCombatPosition)
    {
        $modifier = 1 + (.018 * ($enemyLevel ** 1.14));
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
        return $combatSpeed * (1 + $enemyLevel/200);
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
        $blockChance = 20 + $enemyLevel/4;
        return min(70, $blockChance);
    }

}
