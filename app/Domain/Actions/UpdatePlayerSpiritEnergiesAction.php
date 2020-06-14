<?php


namespace App\Domain\Actions;


use App\Domain\Collections\HeroCollection;
use App\Domain\Collections\PlayerSpiritCollection;
use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Week;
use App\Domain\QueryBuilders\PlayerSpiritQueryBuilder;

class UpdatePlayerSpiritEnergiesAction
{
    public const ENERGY_COEFFICIENT_CONSTANT = PlayerSpirit::STARTING_ENERGY/5;
    public const ENERGY_COEFFICIENT_EXPONENT = .5;

    public function execute()
    {
        $week = Week::current();
        $spiritsInUseCount = $this->getSpiritsInUseCount($week);
        $spiritsInUseOverEnergyAdjustmentMin = $spiritsInUseCount - PlayerSpirit::MAX_USAGE_BEFORE_ENERGY_ADJUSTMENT;

        $spiritsForWeekQuery = PlayerSpirit::query()->forWeek($week);

        // If we have enough spirits in use, we'll adjust energies, otherwise reset them to the default amount
        if ($spiritsInUseOverEnergyAdjustmentMin > 0) {

            $globalEssencePaidFor = $this->getGlobalEssencePaidFor($week);

            $globalSpiritEssenceCost = $spiritsForWeekQuery->sum('essence_cost');

            if ($globalSpiritEssenceCost <= 0) {
                throw new \InvalidArgumentException('Global spirit essence cost is NOT greater than zero');
            }

            $spiritsForWeekQuery->withCount('heroes')->chunkById(100, function(PlayerSpiritCollection $playerSpirits) use ($globalSpiritEssenceCost, $globalEssencePaidFor, $spiritsInUseOverEnergyAdjustmentMin) {

                $playerSpirits->each(function (PlayerSpirit $playerSpirit) use ($globalSpiritEssenceCost, $globalEssencePaidFor, $spiritsInUseOverEnergyAdjustmentMin) {

                    $playerSpirit->energy = $this->getUpdatedEnergy($playerSpirit, $globalSpiritEssenceCost, $globalEssencePaidFor, $spiritsInUseOverEnergyAdjustmentMin);
                    $playerSpirit->save();
                });
            });
        } else {
            $spiritsForWeekQuery->where('energy', '<>', PlayerSpirit::STARTING_ENERGY)
                ->update([
                    'energy' => PlayerSpirit::STARTING_ENERGY
                ]);
        }
    }

    /**
     * @param Week $week
     * @return int
     */
    protected function getSpiritsInUseCount(Week $week): int
    {
        $spiritsInUseCount = Hero::query()->whereHas('playerSpirit', function (PlayerSpiritQueryBuilder $builder) use ($week) {
            return $builder->forWeek($week);
        })->count();
        return $spiritsInUseCount;
    }

    /**
     * @param Week $week
     * @return int
     */
    protected function getGlobalEssencePaidFor(Week $week): int
    {
        $globalEssenceCost = 0;

        Hero::query()->orderBy('id')->whereHas('playerSpirit', function (PlayerSpiritQueryBuilder $builder) use ($week, &$globalEssenceCost) {
            return $builder->forWeek($week);
        })->with('playerSpirit')->chunk(500, function(HeroCollection $heroes) use (&$globalEssenceCost) {

            $globalEssenceCost += $heroes->sum(function (Hero $hero) {
                return $hero->playerSpirit->essence_cost;
            });
        });

        return $globalEssenceCost;
    }

    /**
     * @param PlayerSpirit $playerSpirit
     * @param int $globalSpiritEssenceCost
     * @param int $globalEssencePaidFor
     * @param int $spiritsInUseOverEnergyAdjustmentMin
     * @return int
     */
    protected function getUpdatedEnergy(PlayerSpirit $playerSpirit, int $globalSpiritEssenceCost, int $globalEssencePaidFor, int $spiritsInUseOverEnergyAdjustmentMin): int
    {
        $energyDelta = $this->getEnergyDelta($playerSpirit, $globalSpiritEssenceCost, $globalEssencePaidFor, $spiritsInUseOverEnergyAdjustmentMin);
        return $energyDelta + PlayerSpirit::STARTING_ENERGY;
    }

    /**
     * @param PlayerSpirit $playerSpirit
     * @param $globalSpiritEssenceCost
     * @param $globalEssencePaidFor
     * @param $spiritsInUseOverEnergyAdjustmentMin
     * @return int
     */
    protected function getEnergyDelta(PlayerSpirit $playerSpirit, int $globalSpiritEssenceCost, int $globalEssencePaidFor, int $spiritsInUseOverEnergyAdjustmentMin): int
    {
        $coefficient = self::ENERGY_COEFFICIENT_CONSTANT * ($spiritsInUseOverEnergyAdjustmentMin ** self::ENERGY_COEFFICIENT_EXPONENT);

        $essenceCostRatio = $playerSpirit->essence_cost / $globalSpiritEssenceCost;

        // heroes_count comes from withCount() method
        $totalEssencePaidForSpirit = $playerSpirit->essence_cost * $playerSpirit->heroes_count;
        $essencePaidForRatio = $totalEssencePaidForSpirit / $globalEssencePaidFor;

        $deltaRatio = abs($essenceCostRatio - $essencePaidForRatio);
        $energyDelta = $coefficient * $deltaRatio;

        /*
         * If delta is 9000 and PlayerSpirit::STARTING_ENERGY is 10000, but MIN_MAX_ENERGY_RATIO is 5
         * The delta will be converted to 8000 because 1000 (the difference) is less than 10000/5
         */
        $absoluteMinEnergy = PlayerSpirit::STARTING_ENERGY / PlayerSpirit::MIN_MAX_ENERGY_RATIO;
        if (PlayerSpirit::STARTING_ENERGY - $energyDelta < $absoluteMinEnergy) {
            $energyDelta = PlayerSpirit::STARTING_ENERGY - $absoluteMinEnergy;
        }

        /*
         * We want an 20% decrease in energy to be the equivalent of a 25% increase in energy
         * so that one player spirit will be at 4/5(80%) the energy, and the other will be at 5/4 (1.25%) the energy
         *
         * ie if one player spirit has a decrease in 50% energy another would have a 200% increase
         */
        $positiveDelta = $essenceCostRatio - $essencePaidForRatio > 0;
        if ($positiveDelta) {
            /*
             * Don't have to worry about division by zero because the $absoluteMinEnergy check
             * prevents $energyDelta from equaling PlayerSpirit::STARTING_ENERGY
             */
            $inverse = PlayerSpirit::STARTING_ENERGY / ( PlayerSpirit::STARTING_ENERGY - $energyDelta );
            $energyDelta = $inverse * $energyDelta;
        }

        $signMultiplier = $positiveDelta ? 1 : -1;
        return (int) round($energyDelta * $signMultiplier);
    }
}
