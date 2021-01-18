<?php

namespace Tests\Unit;

use App\Domain\Actions\SquadBorderTravelAction;
use App\Domain\Models\Continent;
use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use App\Domain\Models\Support\Squads\SquadBorderTravelCostCalculator;
use App\Exceptions\SquadTravelException;
use App\Jobs\CreateSquadEntersProvinceEventJob;
use App\Jobs\CreateSquadLeavesProvinceEventJob;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SquadBorderTravelActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Squad */
    protected $squad;

    /** @var Province */
    protected $startingProvince;

    /** @var Province */
    protected $border;

    /** @var SquadBorderTravelAction  */
    protected $domainAction;

    public function setUp(): void
    {
        parent::setUp();

        $this->squad = factory(Squad::class)->create();
        $this->startingProvince = $this->squad->province;
        $this->border = $this->squad->province->borders()->inRandomOrder()->first();
        $this->domainAction = app(SquadBorderTravelAction::class);
    }

    /**
     * @test
     */
    public function a_squad_can_border_travel()
    {
        Queue::fake();

        /*
         * Force travel cost to be less than available gold
         */
        $availableGold = $this->squad->getAvailableGold();
        $this->assertGreaterThan(0, $availableGold);
        $cost = $availableGold - 1;
        $costCalculatorMock = \Mockery::mock(SquadBorderTravelCostCalculator::class);
        $costCalculatorMock->shouldReceive('calculateGoldCost')->andReturn($cost);
        // put the mock into the container
        app()->instance(SquadBorderTravelCostCalculator::class, $costCalculatorMock);

        // need to pull out of the container against since we injected a mock dependency
        $this->domainAction = app(SquadBorderTravelAction::class);
        $this->domainAction->execute($this->squad, $this->border);

        $squad = $this->squad->fresh();

        $this->assertEquals($availableGold - $cost, $squad->getAvailableGold());
        $this->assertEquals($this->border->id, $squad->province_id);
    }

        /**
     * @test
     */
    public function it_will_throw_an_exception_if_they_dont_border_each_other()
    {
        Queue::fake();

        /** @var Province $nonBorder */
        $nonBorder = Province::query()->whereDoesntHave('borders', function(Builder $builder){
            return $builder->where('id', '=', $this->startingProvince->id);
        })->first();

        try {

            $this->domainAction->execute($this->squad, $nonBorder);

        } catch (SquadTravelException $exception) {

            $squad = $this->squad->fresh();
            $this->assertEquals(SquadTravelException::NOT_BORDERED_BY, $exception->getCode());
            $this->assertEquals($this->startingProvince->id, $squad->province_id);
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_squad_cannot_afford_the_travel_expenses()
    {
        Queue::fake();

        /*
         * Force travel cost to be too expensive
         */
        $availableGold = $this->squad->getAvailableGold();
        $cost = $availableGold + 1;
        $costCalculatorMock = \Mockery::mock(SquadBorderTravelCostCalculator::class);
        $costCalculatorMock->shouldReceive('calculateGoldCost')->andReturn($cost);
        // put the mock into the container
        app()->instance(SquadBorderTravelCostCalculator::class, $costCalculatorMock);

        try {
            // need to pull out of the container against since we injected a mock dependency
            $this->domainAction = app(SquadBorderTravelAction::class);
            $this->domainAction->execute($this->squad, $this->border);

        } catch (SquadTravelException $exception) {

            $squad = $this->squad->fresh();
            $this->assertEquals(SquadTravelException::NOT_ENOUGH_GOLD, $exception->getCode());
            $this->assertEquals($availableGold, $squad->getAvailableGold());
            $this->assertEquals($this->startingProvince->id, $squad->province_id);
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @param $continentName
     * @test
     * @dataProvider provides_a_squad_cannot_travel_into_certain_continents_until_the_min_level_is_reached
     */
    public function a_squad_can_travel_into_certain_continents_only_when_the_min_level_requirement_is_reached($continentName)
    {
        Queue::fake();

        /** @var Continent $continent */
        $continent = Continent::forName($continentName);
        $continentID = $continent->id;
        $minLevel = $continent->getBehavior()->getMinLevelRequirement();

        /** @var Province $provinceInContinent */
        $provinceInContinent = Province::query()->ofContinent($continentID)->inRandomOrder()->first();
        $this->squad->province_id = $provinceInContinent->id;
        $this->squad->save();

        /** @var Province $borderOfSameContinent */
        $borderOfSameContinent = $provinceInContinent->borders()->ofContinent($continentID)->inRandomOrder()->first();

        /*
         * Mock the squad so that it returns a level under the minimum level requirement of the continent
         */
        /** @var Squad $mockedSquad */
        $mockedSquad = \Mockery::mock($this->squad->fresh())->shouldReceive('level')->andReturn($minLevel - 1)->getMock();

        $exceptionThrown = false;
        try {
            $this->domainAction->execute($mockedSquad, $borderOfSameContinent);
        } catch (SquadTravelException $exception) {
            $this->assertEquals(SquadTravelException::MIN_LEVEL_NOT_MET, $exception->getCode());
            $this->assertEquals($provinceInContinent->id, $this->squad->fresh()->province_id);
            $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown, "Exception not thrown");

        /*
         * Try again and test it succeeds by mocking getLevel to return THE minimum level
         * requirement and forcing the travel cost to be zero
         */
        /** @var Squad $mockedSquad */
        $mockedSquad = \Mockery::mock($this->squad->fresh())->shouldReceive('level')->andReturn($minLevel)->getMock();
        $costCalculatorMock = \Mockery::mock(SquadBorderTravelCostCalculator::class);
        $costCalculatorMock->shouldReceive('calculateGoldCost')->andReturn(0);
        // put the mock into the container
        app()->instance(SquadBorderTravelCostCalculator::class, $costCalculatorMock);

        // need to pull out of the container against since we injected a mock dependency
        /** @var SquadBorderTravelAction domainAction */
        $domainAction = app(SquadBorderTravelAction::class);
        $domainAction->execute($mockedSquad, $borderOfSameContinent);

        $this->assertEquals($borderOfSameContinent->id, $this->squad->fresh()->province_id);
    }

    public function provides_a_squad_cannot_travel_into_certain_continents_until_the_min_level_is_reached()
    {
        /*
         * Note: Fetroya (starting continent) has no level requirement an thus doesn't need to be tested
         */
        return [
            [
                'continentName' => Continent::NORTH_JAGONETH
            ],
            [
                'continentName' => Continent::EAST_WOZUL
            ],
            [
                'continentName' => Continent::CENTRAL_JAGONETH
            ],
            [
                'continentName' => Continent::WEST_WOZUL
            ],
            [
                'continentName' => Continent::SOUTH_JAGONETH
            ],
            [
                'continentName' => Continent::VINDOBERON
            ],
            [
                'continentName' => Continent::DEMAUXOR
            ]
        ];
    }

    /**
     * @test
     */
    public function it_will_dispatch_a_create_squad_enters_province_event_job()
    {
        Queue::fake();

        $originalLocation = $this->squad->province;
        // need to pull out of the container against since we injected a mock dependency
        $this->domainAction = app(SquadBorderTravelAction::class);
        $this->domainAction->execute($this->squad, $this->border);

        Queue::assertPushed(CreateSquadEntersProvinceEventJob::class, function (CreateSquadEntersProvinceEventJob $job) use ($originalLocation) {
            return $job->squad->id === $this->squad->id
                && $job->provinceEntered->id === $this->border->id
                && $job->provinceLeft->id === $originalLocation->id;
        });
    }
    /**
     * @test
     */
    public function it_will_dispatch_a_create_squad_leaves_province_event_job()
    {
        Queue::fake();

        $originalLocation = $this->squad->province;
        // need to pull out of the container against since we injected a mock dependency
        $this->domainAction = app(SquadBorderTravelAction::class);
        $this->domainAction->execute($this->squad, $this->border);

        Queue::assertPushed(CreateSquadLeavesProvinceEventJob::class, function (CreateSquadLeavesProvinceEventJob $job) use ($originalLocation) {
            return $job->squad->id === $this->squad->id
                && $job->provinceEntered->id === $this->border->id
                && $job->provinceLeft->id === $originalLocation->id;
        });
    }
}
