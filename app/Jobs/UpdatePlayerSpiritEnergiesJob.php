<?php

namespace App\Jobs;

use App\Domain\Collections\HeroCollection;
use App\Domain\Collections\PlayerSpiritCollection;
use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Week;
use App\Domain\QueryBuilders\PlayerSpiritQueryBuilder;
use Carbon\Exceptions\InvalidDateException;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use MathPHP\Arithmetic;

class UpdatePlayerSpiritEnergiesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public const ENERGY_CALCULATION_CONSTANT = 2000;
    public const ENERGY_CALCULATION_EXPONENT = .5;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $week = Week::current();

        $spiritsInUseCount = $this->getSpiritsInUseCount($week);
        $spiritsInUseOverEnergyAdjustmentMin = $spiritsInUseCount - PlayerSpirit::MAX_USAGE_BEFORE_ENERGY_ADJUSTMENT;

        // If we have enough spirits in use, we'll adjust energies, otherwise reset them to the default amount
        if ($spiritsInUseOverEnergyAdjustmentMin > 0) {

            $globalEssencePaidFor = $this->getGlobalEssencePaidFor($week);

            $query = PlayerSpirit::query()->forWeek($week);
            $globalSpiritEssenceCost = $query->sum('essence_cost');

            if ($globalSpiritEssenceCost <= 0) {
                throw new \InvalidArgumentException('Global spirit essence cost is NOT greater than zero');
            }

            $query->withCount('heroes')->chunkById(100, function(PlayerSpiritCollection $playerSpirits) use ($globalSpiritEssenceCost, $globalEssencePaidFor, $spiritsInUseOverEnergyAdjustmentMin) {


                $playerSpirits->each(function (PlayerSpirit $playerSpirit) use ($globalSpiritEssenceCost, $globalEssencePaidFor, $spiritsInUseOverEnergyAdjustmentMin) {

                    $playerSpirit->energy = $this->getUpdatedEnergy($playerSpirit, $globalSpiritEssenceCost, $globalEssencePaidFor, $spiritsInUseOverEnergyAdjustmentMin);
                    $playerSpirit->save();
                });
            });
        } else {
            PlayerSpirit::query()->forWeek($week)
                ->where('energy', '<>', PlayerSpirit::STARTING_ENERGY)
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
        return max($energyDelta + PlayerSpirit::STARTING_ENERGY, PlayerSpirit::MIN_POSSIBLE_ENERGY);
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
        // heroes_count comes from withCount() method
        $totalEssencePaidForSpirit = $playerSpirit->essence_cost * $playerSpirit->heroes_count;
        $essenceCostRatio = $playerSpirit->essence_cost / $globalSpiritEssenceCost;
        $essencePaidForRatio = $totalEssencePaidForSpirit / $globalEssencePaidFor;
        return (int) round(($essenceCostRatio - $essencePaidForRatio) * self::ENERGY_CALCULATION_CONSTANT * ($spiritsInUseOverEnergyAdjustmentMin ** self::ENERGY_CALCULATION_EXPONENT));
    }
}
