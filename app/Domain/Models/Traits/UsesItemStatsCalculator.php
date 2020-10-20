<?php


namespace App\Domain\Models\Traits;


use App\Domain\Interfaces\UsesItems;
use App\Domain\Models\Support\Items\ItemStatsCalculator;
use Illuminate\Support\Collection;

/**
 * Trait UsesItemStatsCalculator
 * @package App\Domain\Models\Traits
 *
 * @property int $item_type_id
 * @property int $material_id
 * @method UsesItems|null getUsesItems()
 */
trait UsesItemStatsCalculator
{

    protected function getStatsCalculator()
    {
        return new ItemStatsCalculator($this->item_type_id, $this->material_id, $this->getUsesItems());
    }

    public function adjustCombatSpeed(float $speed): float
    {
        return $this->getStatsCalculator()->adjustCombatSpeed($speed);
    }

    public function adjustBaseDamage(float $baseDamage): float
    {
        return $this->getStatsCalculator()->adjustBaseDamage($baseDamage);
    }

    public function adjustDamageMultiplier(float $damageMultiplier): float
    {
        return $this->getStatsCalculator()->adjustDamageMultiplier($damageMultiplier);
    }

    public function adjustResourceCostAmount(float $amount): int
    {
        return $this->getStatsCalculator()->adjustResourceCostAmount($amount);
    }

    public function adjustResourceCostPercent(float $amount): float
    {
        return $this->getStatsCalculator()->adjustResourceCostPercent($amount);
    }

    public function getValidGearSlotTypes(): array
    {
        return $this->getStatsCalculator()->getValidGearSlotTypes();
    }

    public function getGearSlotsNeededCount(): int
    {
        return $this->getStatsCalculator()->getGearSlotsNeededCount();
    }

    public function adjustResourceCosts(Collection $resourceCosts): Collection
    {
        return $this->getStatsCalculator()->adjustResourceCosts($resourceCosts);
    }
}
