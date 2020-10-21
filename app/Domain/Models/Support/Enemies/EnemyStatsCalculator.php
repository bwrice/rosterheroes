<?php


namespace App\Domain\Models\Support\Enemies;

use App\Facades\CombatPositionFacade;
use App\Facades\EnemyTypeFacade;
use Illuminate\Support\Collection;

class EnemyStatsCalculator
{
    protected int $enemyTypeID;
    protected int $enemyLevel;
    protected int $combatPositionID;

    public function __construct(int $enemyTypeID, int $enemyLevel, int $combatPositionID)
    {
        $this->enemyTypeID = $enemyTypeID;
        $this->enemyLevel = $enemyLevel;
        $this->combatPositionID = $combatPositionID;
    }

    protected function getEnemyTypeBehavior()
    {
        return EnemyTypeFacade::getBehavior($this->enemyTypeID);
    }

    public function getStartingHealth(): int
    {
        return $this->getEnemyTypeBehavior()->getStartingHealth($this->enemyLevel, $this->getCombatPositionName());
    }

    public function getStartingStamina(): int
    {
        return 999999;
    }

    public function getStartingMana(): int
    {
        return 999999;
    }

    public function getProtection(): int
    {
        return $this->getEnemyTypeBehavior()->getProtection($this->enemyLevel, $this->getCombatPositionName());
    }

    public function getBlockChance(): float
    {
        return $this->getEnemyTypeBehavior()->getBlockChancePercent($this->enemyLevel, $this->getCombatPositionName());
    }

    public function adjustBaseDamage(float $baseDamage): float
    {
        return $this->getEnemyTypeBehavior()->adjustBaseDamage($baseDamage, $this->enemyLevel, $this->getCombatPositionName());
    }

    public function adjustCombatSpeed(float $speed): float
    {
        return $this->getEnemyTypeBehavior()->adjustCombatSpeed($speed, $this->enemyLevel, $this->getCombatPositionName());
    }

    public function adjustDamageMultiplier(float $damageMultiplier): float
    {
        return $this->getEnemyTypeBehavior()->adjustDamageMultiplier($damageMultiplier, $this->enemyLevel, $this->getCombatPositionName());
    }

    protected function getCombatPositionName()
    {
        return CombatPositionFacade::getNameByID($this->combatPositionID);
    }

    public function adjustResourceCostAmount(float $amount): int
    {
        return 0;
    }

    public function adjustResourceCostPercent(float $amount): float
    {
        return 0;
    }

    public function adjustResourceCosts(Collection $resourceCosts): Collection
    {
        return $resourceCosts;
    }
}
