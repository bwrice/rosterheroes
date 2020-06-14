<?php

namespace Tests\Feature;

use App\Domain\Actions\UpdatePlayerSpiritEnergiesAction;
use App\Domain\Models\Hero;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Week;
use App\Domain\QueryBuilders\PlayerSpiritQueryBuilder;
use App\Facades\CurrentWeek;
use App\Jobs\UpdatePlayerSpiritEnergiesJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdatePlayerSpiritEnergyActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Week */
    protected $week;

    /** @var UpdatePlayerSpiritEnergiesAction */
    protected $domainAction;

    public function setUp(): void
    {
        parent::setUp();

        $this->week = factory(Week::class)->states('as-current')->create();

        $this->domainAction = app(UpdatePlayerSpiritEnergiesAction::class);
    }

    /**
     * @test
     */
    public function it_wont_adjust_energies_if_below_min_usage()
    {

        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = factory(PlayerSpirit::class)->create([
            'week_id' => $this->week->id
        ]);

        $beforeAdjustmentEnergy = $playerSpirit->energy;

        $heroesToAssignSpiritsTo = PlayerSpirit::MAX_USAGE_BEFORE_ENERGY_ADJUSTMENT - 1;

        factory(Hero::class, $heroesToAssignSpiritsTo)->create([
            'player_spirit_id' => $playerSpirit->id
        ]);

        $playerSpiritUsedForWeekCount = Hero::query()->whereHas('playerSpirit', function (PlayerSpiritQueryBuilder $builder) {
            return $builder->forWeek($this->week);
        })->count();

        $this->assertEquals($heroesToAssignSpiritsTo, $playerSpiritUsedForWeekCount);

        $this->domainAction->execute();

        $playerSpirit = $playerSpirit->fresh();

        $this->assertEquals($beforeAdjustmentEnergy, $playerSpirit->energy);
    }

    /**
     * @test
     */
    public function it_will_lower_the_energy_of_a_player_spirit_used_a_lot()
    {
        /** @var PlayerSpirit $usedALotSpirit */
        $usedALotSpirit = factory(PlayerSpirit::class)->create([
            'week_id' => $this->week->id
        ]);

        // create another PlayerSpirit with a high essence cost
        factory(PlayerSpirit::class)->create([
            'week_id' => $this->week->id
        ]);

        $beforeAdjustmentEnergy = $usedALotSpirit->energy;

        $usedALotSpiritHeroesCount = PlayerSpirit::MAX_USAGE_BEFORE_ENERGY_ADJUSTMENT + 5;

        factory(Hero::class, $usedALotSpiritHeroesCount)->create([
            'player_spirit_id' => $usedALotSpirit->id
        ]);

        /** @var PlayerSpirit $usedVeryLittleSpirit */
        $usedVeryLittleSpirit = factory(PlayerSpirit::class)->create([
            'week_id' => $this->week->id
        ]);

        $usedVeryLittleSpiritHeroesCount = 2;

        factory(Hero::class, $usedVeryLittleSpiritHeroesCount)->create([
            'player_spirit_id' => $usedVeryLittleSpirit->id
        ]);

        $playerSpiritUsedForWeekCount = Hero::query()->whereHas('playerSpirit', function (PlayerSpiritQueryBuilder $builder) {
            return $builder->forWeek($this->week);
        })->count();

        $this->assertEquals($usedALotSpiritHeroesCount + $usedVeryLittleSpiritHeroesCount, $playerSpiritUsedForWeekCount);

        $this->domainAction->execute();

        $usedALotSpirit = $usedALotSpirit->fresh();

        $this->assertLessThan($beforeAdjustmentEnergy, $usedALotSpirit->energy);
    }

    /**
     * @test
     */
    public function it_will_raise_the_energy_a_spirit_used_very_little()
    {
        /** @var PlayerSpirit $usedALotSpirit */
        $usedALotSpirit = factory(PlayerSpirit::class)->create([
            'week_id' => $this->week->id
        ]);

        // create another PlayerSpirit with a high essence cost
        factory(PlayerSpirit::class)->create([
            'week_id' => $this->week->id
        ]);

        $usedALotSpiritHeroesCount = PlayerSpirit::MAX_USAGE_BEFORE_ENERGY_ADJUSTMENT + 5;

        factory(Hero::class, $usedALotSpiritHeroesCount)->create([
            'player_spirit_id' => $usedALotSpirit->id
        ]);

        /** @var PlayerSpirit $usedVeryLittleSpirit */
        $usedVeryLittleSpirit = factory(PlayerSpirit::class)->create([
            'week_id' => $this->week->id
        ]);

        $beforeAdjustmentEnergy = $usedVeryLittleSpirit->energy;

        $usedVeryLittleSpiritHeroesCount = 2;

        factory(Hero::class, $usedVeryLittleSpiritHeroesCount)->create([
            'player_spirit_id' => $usedVeryLittleSpirit->id
        ]);

        $playerSpiritUsedForWeekCount = Hero::query()->whereHas('playerSpirit', function (PlayerSpiritQueryBuilder $builder) {
            return $builder->forWeek($this->week);
        })->count();

        $this->assertEquals($usedALotSpiritHeroesCount + $usedVeryLittleSpiritHeroesCount, $playerSpiritUsedForWeekCount);

        $this->domainAction->execute();

        $usedVeryLittleSpirit = $usedVeryLittleSpirit->fresh();

        $this->assertGreaterThan($beforeAdjustmentEnergy, $usedVeryLittleSpirit->energy);
    }

    /**
     * @test
     */
    public function it_will_raise_the_energy_of_a_player_spirit_not_used()
    {
        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = factory(PlayerSpirit::class)->create([
            'week_id' => $this->week->id
        ]);

        /** @var PlayerSpirit $notUsedPlayerSpirit */
        $notUsedPlayerSpirit = factory(PlayerSpirit::class)->create([
            'week_id' => $this->week->id
        ]);

        $beforeAdjustmentEnergy = $notUsedPlayerSpirit->energy;

        $heroesToAssignSpiritsTo = PlayerSpirit::MAX_USAGE_BEFORE_ENERGY_ADJUSTMENT + 5;

        factory(Hero::class, $heroesToAssignSpiritsTo)->create([
            'player_spirit_id' => $playerSpirit->id
        ]);

        $playerSpiritUsedForWeekCount = Hero::query()->whereHas('playerSpirit', function (PlayerSpiritQueryBuilder $builder) {
            return $builder->forWeek($this->week);
        })->count();

        $this->assertEquals($heroesToAssignSpiritsTo, $playerSpiritUsedForWeekCount);

        $this->domainAction->execute();

        $notUsedPlayerSpirit = $notUsedPlayerSpirit->fresh();

        $this->assertGreaterThan($beforeAdjustmentEnergy, $notUsedPlayerSpirit->energy);
    }

    /**
     * @test
     */
    public function higher_essence_cost_player_spirits_will_have_a_larger_energy_adjustment()
    {
        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = factory(PlayerSpirit::class)->create([
            'week_id' => $this->week->id
        ]);

        /** @var PlayerSpirit $higherEssenceCostSpirit */
        $higherEssenceCostSpirit = factory(PlayerSpirit::class)->create([
            'week_id' => $this->week->id,
            'essence_cost' => 15000
        ]);

        $beforeAdjustmentEnergyForHighCostSpirit = $higherEssenceCostSpirit->energy;

        $highCostSpiritHeroesCount = 3;

        factory(Hero::class, $highCostSpiritHeroesCount)->create([
            'player_spirit_id' => $higherEssenceCostSpirit->id
        ]);

        /** @var PlayerSpirit $lowerEssenceCostSpirit */
        $lowerEssenceCostSpirit = factory(PlayerSpirit::class)->create([
            'week_id' => $this->week->id,
            'essence_cost' => 3000
        ]);

        $lowCostSpiritHeroesCount = 3;

        factory(Hero::class, $lowCostSpiritHeroesCount)->create([
            'player_spirit_id' => $lowerEssenceCostSpirit->id
        ]);

        $beforeAdjustmentEnergyForLowCostSpirit = $lowerEssenceCostSpirit->energy;

        $randomSpiritHeroesCount = PlayerSpirit::MAX_USAGE_BEFORE_ENERGY_ADJUSTMENT + 5;

        factory(Hero::class, $randomSpiritHeroesCount)->create([
            'player_spirit_id' => $playerSpirit->id
        ]);

        $playerSpiritUsedForWeekCount = Hero::query()->whereHas('playerSpirit', function (PlayerSpiritQueryBuilder $builder) {
            return $builder->forWeek($this->week);
        })->count();

        $this->assertEquals($randomSpiritHeroesCount + $highCostSpiritHeroesCount + $lowCostSpiritHeroesCount, $playerSpiritUsedForWeekCount);

        $this->domainAction->execute();

        $higherEssenceCostSpirit = $higherEssenceCostSpirit->fresh();
        $lowerEssenceCostSpirit = $lowerEssenceCostSpirit->fresh();

        $highCostDelta = $higherEssenceCostSpirit->energy - $beforeAdjustmentEnergyForHighCostSpirit;
        $lowCostDelta = $lowerEssenceCostSpirit->energy - $beforeAdjustmentEnergyForLowCostSpirit;

        $this->assertGreaterThan($lowCostDelta, $highCostDelta);
    }

    /**
     * @test
     */
    public function energy_will_be_set_to_default_if_adjustment_usage_requirements_not_met()
    {
        /** @var PlayerSpirit $playerSpirit */
        $playerSpirit = factory(PlayerSpirit::class)->create([
            'week_id' => $this->week->id,
            'energy' => 12345 // energy is not the starting energy
        ]);

        $heroesToAssignSpiritsTo = PlayerSpirit::MAX_USAGE_BEFORE_ENERGY_ADJUSTMENT - 1;

        factory(Hero::class, $heroesToAssignSpiritsTo)->create([
            'player_spirit_id' => $playerSpirit->id
        ]);

        $playerSpiritUsedForWeekCount = Hero::query()->whereHas('playerSpirit', function (PlayerSpiritQueryBuilder $builder) {
            return $builder->forWeek($this->week);
        })->count();

        $this->assertEquals($heroesToAssignSpiritsTo, $playerSpiritUsedForWeekCount);

        $this->domainAction->execute();

        $playerSpirit = $playerSpirit->fresh();

        $this->assertEquals(PlayerSpirit::STARTING_ENERGY, $playerSpirit->energy);
    }

    /**
     * @test
     */
    public function an_energy_increase_will_be_the_inverse_of_the_equivalent_energy_decrease()
    {
        // ie a 20% decrease in energy 4/5 will be a 25% increase in energy 5/4
        /** @var PlayerSpirit $playerSpiritOne */
        $playerSpiritOne = factory(PlayerSpirit::class)->create([
            'week_id' => $this->week->id,
            'essence_cost' => 4000
        ]);

        $beforeAdjustmentEnergyOne = $playerSpiritOne->energy;

        /** @var PlayerSpirit $playerSpiritTwo */
        $playerSpiritTwo = factory(PlayerSpirit::class)->create([
            'week_id' => $this->week->id,
            'essence_cost' => 9000
        ]);

        $beforeAdjustmentEnergyTwo = $playerSpiritTwo->energy;

        $playerSpiritOneHeroesCount = PlayerSpirit::MAX_USAGE_BEFORE_ENERGY_ADJUSTMENT + 5;

        factory(Hero::class, $playerSpiritOneHeroesCount)->create([
            'player_spirit_id' => $playerSpiritOne->id
        ]);

        $playerSpiritTwoHeroesCount = 5;

        factory(Hero::class, $playerSpiritTwoHeroesCount)->create([
            'player_spirit_id' => $playerSpiritTwo->id
        ]);

        $playerSpiritUsedForWeekCount = Hero::query()->whereHas('playerSpirit', function (PlayerSpiritQueryBuilder $builder) {
            return $builder->forWeek($this->week);
        })->count();

        $this->assertEquals($playerSpiritOneHeroesCount + $playerSpiritTwoHeroesCount, $playerSpiritUsedForWeekCount);

        $this->domainAction->execute();

        $playerSpiritOne = $playerSpiritOne->fresh();
        $playerSpiritTwo = $playerSpiritTwo->fresh();

        /*
         * $playerSpiritOne will be decreased in energy because it's the only one being used by heroes
         * 2 significant figures should be enough to confirm
         */
        $ratioAsPercentOne = (int) (round($playerSpiritOne->energy/$beforeAdjustmentEnergyOne, 2) * 100);
        $ratioAsPercentTwo = (int) (round($beforeAdjustmentEnergyTwo/$playerSpiritTwo->energy, 2) * 100);

        $this->assertEquals($ratioAsPercentOne, $ratioAsPercentTwo);
    }
}
