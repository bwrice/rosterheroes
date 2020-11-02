<?php


namespace App\Domain\Models\Traits;


use App\Domain\Models\Support\Enemies\EnemyStatsCalculator;
use Illuminate\Support\Collection;

/**
 * Class UsesEnemyStatsCalculator
 * @package App\Domain\Models\Traits
 *
 * @property int $level
 * @property int $enemy_type_id
 * @property int $combat_position_id
 */
trait UsesEnemyStatsCalculator
{
    protected function enemyStatsCalculator()
    {
        return new EnemyStatsCalculator($this->enemy_type_id, $this->level, $this->combat_position_id);
    }

    public function getStartingHealth(): int
    {
        return $this->enemyStatsCalculator()->getStartingHealth();
    }

    public function getStartingStamina(): int
    {
        return $this->enemyStatsCalculator()->getStartingStamina();
    }

    public function getStartingMana(): int
    {
        return $this->enemyStatsCalculator()->getStartingMana();
    }

    public function getProtection(): int
    {
        return $this->enemyStatsCalculator()->getProtection();
    }

    public function getBlockChance(): float
    {
        return $this->enemyStatsCalculator()->getBlockChance();
    }

    public function adjustBaseDamage(float $baseDamage): float
    {
        return $this->enemyStatsCalculator()->adjustBaseDamage($baseDamage);
    }

    public function adjustCombatSpeed(float $speed): float
    {
        return $this->enemyStatsCalculator()->adjustCombatSpeed($speed);
    }

    public function adjustDamageMultiplier(float $damageMultiplier): float
    {
        return $this->enemyStatsCalculator()->adjustDamageMultiplier($damageMultiplier);
    }

    public function adjustResourceCostAmount(float $amount): int
    {
        return $this->enemyStatsCalculator()->adjustResourceCostAmount($amount);
    }

    public function adjustResourceCostPercent(float $amount): float
    {
        return $this->enemyStatsCalculator()->adjustResourceCostPercent($amount);
    }

    public function adjustResourceCosts(Collection $resourceCosts): Collection
    {
        return $this->enemyStatsCalculator()->adjustResourceCosts($resourceCosts);
    }
}
