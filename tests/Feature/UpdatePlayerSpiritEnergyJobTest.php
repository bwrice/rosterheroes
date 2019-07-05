<?php

namespace Tests\Feature;

use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Week;
use App\Domain\QueryBuilders\PlayerSpiritQueryBuilder;
use App\Jobs\UpdatePlayerSpiritEnergiesJob;
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

        $heroesToAssignSpiritsTo = PlayerSpirit::MAX_USAGE_BEFORE_ENERGY_ADJUSTMENT;
        foreach(range(1, $heroesToAssignSpiritsTo) as $count) {
            factory(Hero::class)->create([
                'player_spirit_id' => $playerSpirit->id
            ]);
        }

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
            'week_id' => $week->id,
            'essence_cost' => 15000
        ]);

        $beforeAdjustmentEnergy = $playerSpirit->energy;

        $heroesToAssignSpiritsTo = PlayerSpirit::MAX_USAGE_BEFORE_ENERGY_ADJUSTMENT + 1;
        foreach(range(1, $heroesToAssignSpiritsTo) as $count) {
            factory(Hero::class)->create([
                'player_spirit_id' => $playerSpirit->id
            ]);
        }

        $playerSpiritUsedForWeekCount = Hero::query()->whereHas('playerSpirit', function (PlayerSpiritQueryBuilder $builder) use ($week) {
            return $builder->forWeek($week);
        })->count();

        $this->assertEquals($heroesToAssignSpiritsTo, $playerSpiritUsedForWeekCount);

        UpdatePlayerSpiritEnergiesJob::dispatchNow();

        $playerSpirit = $playerSpirit->fresh();

        $this->assertLessThan($beforeAdjustmentEnergy, $playerSpirit->energy);
    }
}
