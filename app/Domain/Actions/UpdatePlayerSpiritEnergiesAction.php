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

    public function execute()
    {
        $week = Week::current();
        $spiritsInUseCount = $this->getSpiritsInUseCount($week);

        $spiritsForWeekQuery = PlayerSpirit::query()->forWeek($week);

        // If we have enough spirits in use, we'll adjust energies, otherwise reset them to the default amount
        if (($spiritsInUseCount - PlayerSpirit::MAX_USAGE_BEFORE_ENERGY_ADJUSTMENT) > 0) {


            $sumOfEssencePaidFor =  $this->getGlobalEssencePaidFor($week);

            $sumOfSpiritsWithHeroesEssenceCost = (int) (clone $spiritsForWeekQuery)->has('heroes')->sum('essence_cost');

            $spiritsForWeekQuery->withCount('heroes')->chunkById(100, function(PlayerSpiritCollection $playerSpirits) use ($sumOfSpiritsWithHeroesEssenceCost, $sumOfEssencePaidFor, $spiritsInUseCount) {

                $playerSpirits->each(function (PlayerSpirit $playerSpirit) use ($sumOfSpiritsWithHeroesEssenceCost, $sumOfEssencePaidFor, $spiritsInUseCount) {

                    $playerSpirit->energy = $this->getUpdatedEnergy($playerSpirit, $sumOfSpiritsWithHeroesEssenceCost, $sumOfEssencePaidFor, $spiritsInUseCount);
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
     * @param int $sumOfSpiritsWithHeroesEssenceCost
     * @param int $sumOfEssencePaidFor
     * @param int $spiritsInUseCount
     * @return int
     */
    protected function getUpdatedEnergy(PlayerSpirit $playerSpirit, int $sumOfSpiritsWithHeroesEssenceCost, int $sumOfEssencePaidFor, int $spiritsInUseCount): int
    {
        $energyDelta = $this->getEnergyDelta($playerSpirit, $sumOfSpiritsWithHeroesEssenceCost, $sumOfEssencePaidFor, $spiritsInUseCount);
        return $energyDelta + PlayerSpirit::STARTING_ENERGY;
    }

    /**
     * @param PlayerSpirit $playerSpirit
     * @param $sumOfSpiritsWithHeroesEssenceCost
     * @param $sumOfEssencePaidFor
     * @param $spiritsInUseCount
     * @return int
     */
    protected function getEnergyDelta(PlayerSpirit $playerSpirit, int $sumOfSpiritsWithHeroesEssenceCost, int $sumOfEssencePaidFor, int $spiritsInUseCount): int
    {
        $essenceCostRatio = $playerSpirit->essence_cost / $sumOfSpiritsWithHeroesEssenceCost;

        /*
         * heroes_count comes from withCount() method
         * Treat spirits without any heroes as if they had 1
         */
        $heroesCount = $playerSpirit->heroes_count ?: 1;
        $totalEssencePaidForSpirit = $playerSpirit->essence_cost * $heroesCount;
        $essencePaidForRatio = $totalEssencePaidForSpirit / $sumOfEssencePaidFor;

        $deltaRatio = abs($essenceCostRatio - $essencePaidForRatio);

        $coefficient = 10 * ((5000 * $spiritsInUseCount) ** .35);

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
