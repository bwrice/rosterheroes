<?php

namespace Tests\Feature;

use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Week;
use App\Domain\QueryBuilders\PlayerSpiritQueryBuilder;
use App\Jobs\UpdatePlayerSpiritEnergiesJob;
use App\Nova\Player;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use function Psy\debug;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdatePlayerSpiritEnergyJobTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_wont_adjust_energies_if_below_min_usage()
    {
        $week = factory(Week::class)->create();
        Week::setTestCurrent($week);

        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = factory(PlayerSpirit::class)->create([
            'week_id' => $week->id
        ]);

        $beforeAdjustmentEnergy = $playerSpirit->energy;

        $heroesToAssignSpiritsTo = PlayerSpirit::MAX_USAGE_BEFORE_ENERGY_ADJUSTMENT + 5;

        factory(Hero::class, $heroesToAssignSpiritsTo)->create([
            'player_spirit_id' => $playerSpirit->id
        ]);

        $playerSpiritUsedForWeekCount = Hero::query()->whereHas('playerSpirit', function (PlayerSpiritQueryBuilder $builder) use ($week) {
            return $builder->forWeek($week);
        })->count();

        $this->assertEquals($heroesToAssignSpiritsTo, $playerSpiritUsedForWeekCount);

        UpdatePlayerSpiritEnergiesJob::dispatchNow();

        $playerSpirit = $playerSpirit->fresh();

        $this->assertEquals($beforeAdjustmentEnergy, $playerSpirit->energy);
    }

    /**
     * @test
     */
    public function it_will_lower_the_energy_of_a_player_spirit_used_a_lot()
    {
        $week = factory(Week::class)->create();
        Week::setTestCurrent($week);

        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = factory(PlayerSpirit::class)->create([
            'week_id' => $week->id
        ]);

        // create another PlayerSpirit with a high essence cost
        factory(PlayerSpirit::class)->create([
            'week_id' => $week->id
        ]);

        $beforeAdjustmentEnergy = $playerSpirit->energy;

        $heroesToAssignSpiritsTo = PlayerSpirit::MAX_USAGE_BEFORE_ENERGY_ADJUSTMENT + 5;

        factory(Hero::class, $heroesToAssignSpiritsTo)->create([
            'player_spirit_id' => $playerSpirit->id
        ]);

        $playerSpiritUsedForWeekCount = Hero::query()->whereHas('playerSpirit', function (PlayerSpiritQueryBuilder $builder) use ($week) {
            return $builder->forWeek($week);
        })->count();

        $this->assertEquals($heroesToAssignSpiritsTo, $playerSpiritUsedForWeekCount);

        UpdatePlayerSpiritEnergiesJob::dispatchNow();

        $playerSpirit = $playerSpirit->fresh();

        $this->assertLessThan($beforeAdjustmentEnergy, $playerSpirit->energy);
    }

    /**
     * @test
     */
    public function it_will_raise_the_energy_of_a_player_spirit_not_used()
    {
        $week = factory(Week::class)->create();
        Week::setTestCurrent($week);

        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = factory(PlayerSpirit::class)->create([
            'week_id' => $week->id
        ]);

        /** @var PlayerSpirit $notUsedPlayerSpirit */
        $notUsedPlayerSpirit = factory(PlayerSpirit::class)->create([
            'week_id' => $week->id
        ]);

        $beforeAdjustmentEnergy = $notUsedPlayerSpirit->energy;

        $heroesToAssignSpiritsTo = PlayerSpirit::MAX_USAGE_BEFORE_ENERGY_ADJUSTMENT + 5;

        factory(Hero::class, $heroesToAssignSpiritsTo)->create([
            'player_spirit_id' => $playerSpirit->id
        ]);

        $playerSpiritUsedForWeekCount = Hero::query()->whereHas('playerSpirit', function (PlayerSpiritQueryBuilder $builder) use ($week) {
            return $builder->forWeek($week);
        })->count();

        $this->assertEquals($heroesToAssignSpiritsTo, $playerSpiritUsedForWeekCount);

        UpdatePlayerSpiritEnergiesJob::dispatchNow();

        $notUsedPlayerSpirit = $notUsedPlayerSpirit->fresh();

        $this->assertGreaterThan($beforeAdjustmentEnergy, $notUsedPlayerSpirit->energy);
    }

    /**
     * @test
     */
    public function higher_essence_cost_player_spirits_will_have_a_larger_energy_adjustment()
    {
        $week = factory(Week::class)->create();
        Week::setTestCurrent($week);

        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = factory(PlayerSpirit::class)->create([
            'week_id' => $week->id
        ]);

        /** @var PlayerSpirit $higherEssenceCostSpirit */
        $higherEssenceCostSpirit = factory(PlayerSpirit::class)->create([
            'week_id' => $week->id,
            'essence_cost' => 15000
        ]);

        $beforeAdjustmentEnergyForHighCostSpirit = $higherEssenceCostSpirit->energy;

        /** @var PlayerSpirit $lowerEssenceCostSpirit */
        $lowerEssenceCostSpirit = factory(PlayerSpirit::class)->create([
            'week_id' => $week->id,
            'essence_cost' => 3000
        ]);

        $beforeAdjustmentEnergyForLowCostSpirit = $lowerEssenceCostSpirit->energy;

        $heroesToAssignSpiritsTo = PlayerSpirit::MAX_USAGE_BEFORE_ENERGY_ADJUSTMENT + 5;

        factory(Hero::class, $heroesToAssignSpiritsTo)->create([
            'player_spirit_id' => $playerSpirit->id
        ]);

        $playerSpiritUsedForWeekCount = Hero::query()->whereHas('playerSpirit', function (PlayerSpiritQueryBuilder $builder) use ($week) {
            return $builder->forWeek($week);
        })->count();

        $this->assertEquals($heroesToAssignSpiritsTo, $playerSpiritUsedForWeekCount);

        UpdatePlayerSpiritEnergiesJob::dispatchNow();

        $higherEssenceCostSpirit = $higherEssenceCostSpirit->fresh();
        $lowerEssenceCostSpirit = $lowerEssenceCostSpirit->fresh();

        $highCostDelta = $higherEssenceCostSpirit->energy - $beforeAdjustmentEnergyForHighCostSpirit;
        $lowCostDelta = $lowerEssenceCostSpirit->energy - $beforeAdjustmentEnergyForLowCostSpirit;

        $this->assertGreaterThan($lowCostDelta, $highCostDelta);
    }

    /**
     * @test
     */
    public function energy_will_never_be_adjusted_below_the_minimum()
    {
        $week = factory(Week::class)->create();
        Week::setTestCurrent($week);

        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = factory(PlayerSpirit::class)->create([
            'week_id' => $week->id,
            'essence_cost' => 1 // Super cheap to help exaggerate the condition
        ]);

        // create another PlayerSpirit with a high essence cost
        factory(PlayerSpirit::class)->create([
            'week_id' => $week->id,
            'essence_cost' => 99999 // Super expensive to help exaggerate the condition
        ]);

        $heroesToAssignSpiritsTo = PlayerSpirit::MAX_USAGE_BEFORE_ENERGY_ADJUSTMENT + 20;

        factory(Hero::class, $heroesToAssignSpiritsTo)->create([
            'player_spirit_id' => $playerSpirit->id
        ]);

        $playerSpiritUsedForWeekCount = Hero::query()->whereHas('playerSpirit', function (PlayerSpiritQueryBuilder $builder) use ($week) {
            return $builder->forWeek($week);
        })->count();

        $this->assertEquals($heroesToAssignSpiritsTo, $playerSpiritUsedForWeekCount);

        UpdatePlayerSpiritEnergiesJob::dispatchNow();

        $playerSpirit = $playerSpirit->fresh();

        $this->assertEquals(PlayerSpirit::MIN_POSSIBLE_ENERGY, $playerSpirit->energy);

    }

    /**
     * @test
     */
    public function energy_will_never_be_adjusted_above_the_maximum()
    {
        $week = factory(Week::class)->create();
        Week::setTestCurrent($week);

        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = factory(PlayerSpirit::class)->create([
            'week_id' => $week->id,
            'essence_cost' => 1 // Super cheap to help exaggerate the condition
        ]);

        // create another PlayerSpirit with a high essence cost
        $notUsedPlayerSpirit = factory(PlayerSpirit::class)->create([
            'week_id' => $week->id,
            'essence_cost' => 99999 // Super expensive to help exaggerate the condition
        ]);

        $heroesToAssignSpiritsTo = PlayerSpirit::MAX_USAGE_BEFORE_ENERGY_ADJUSTMENT + 20;

        factory(Hero::class, $heroesToAssignSpiritsTo)->create([
            'player_spirit_id' => $playerSpirit->id
        ]);

        $playerSpiritUsedForWeekCount = Hero::query()->whereHas('playerSpirit', function (PlayerSpiritQueryBuilder $builder) use ($week) {
            return $builder->forWeek($week);
        })->count();

        $this->assertEquals($heroesToAssignSpiritsTo, $playerSpiritUsedForWeekCount);

        UpdatePlayerSpiritEnergiesJob::dispatchNow();

        $notUsedPlayerSpirit = $notUsedPlayerSpirit->fresh();

        $this->assertEquals(PlayerSpirit::MAX_POSSIBLE_ENERGY, $notUsedPlayerSpirit->energy);
    }

    /**
     * @test
     */
    public function energy_will_be_set_to_default_if_adjustment_usage_requirements_not_met()
    {
        $week = factory(Week::class)->create();
        Week::setTestCurrent($week);

        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = factory(PlayerSpirit::class)->create([
            'week_id' => $week->id,
            'energy' => 12345 // energy is not the starting energy
        ]);

        $heroesToAssignSpiritsTo = PlayerSpirit::MAX_USAGE_BEFORE_ENERGY_ADJUSTMENT - 1;

        factory(Hero::class, $heroesToAssignSpiritsTo)->create([
            'player_spirit_id' => $playerSpirit->id
        ]);

        $playerSpiritUsedForWeekCount = Hero::query()->whereHas('playerSpirit', function (PlayerSpiritQueryBuilder $builder) use ($week) {
            return $builder->forWeek($week);
        })->count();

        $this->assertEquals($heroesToAssignSpiritsTo, $playerSpiritUsedForWeekCount);

        UpdatePlayerSpiritEnergiesJob::dispatchNow();

        $playerSpirit = $playerSpirit->fresh();

        $this->assertEquals(PlayerSpirit::STARTING_ENERGY, $playerSpirit->energy);
    }
}
