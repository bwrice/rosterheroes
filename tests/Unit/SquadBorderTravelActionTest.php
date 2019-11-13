<?php

namespace Tests\Unit;

use App\Domain\Actions\SquadBorderTravelAction;
use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use App\Domain\Models\Support\Squads\SquadBorderTravelCostCalculator;
use App\Exceptions\SquadTravelException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
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

    public function setUp(): void
    {
        parent::setUp();

        $this->squad = factory(Squad::class)->create();
        $this->startingProvince = $this->squad->province;
        $this->border = $this->squad->province->borders()->inRandomOrder()->first();
    }

    /**
     * @test
     */
    public function a_squad_can_border_travel()
    {
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

        /** @var SquadBorderTravelAction  $borderTravelAction */
        $borderTravelAction = app(SquadBorderTravelAction::class);
        $borderTravelAction->execute($this->squad, $this->border);

        $squad = $this->squad->fresh();

        $this->assertEquals($availableGold - $cost, $squad->getAvailableGold());
        $this->assertEquals($this->border->id, $squad->province_id);
    }

        /**
     * @test
     */
    public function it_will_throw_an_exception_if_they_dont_border_each_other()
    {
        /** @var Province $nonBorder */
        $nonBorder = Province::query()->whereDoesntHave('borders', function(Builder $builder){
            return $builder->where('id', '=', $this->startingProvince->id);
        })->first();

        try {
            /** @var SquadBorderTravelAction  $borderTravelAction */
            $borderTravelAction = app(SquadBorderTravelAction::class);
            $borderTravelAction->execute($this->squad, $nonBorder);

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
            /** @var SquadBorderTravelAction  $borderTravelAction */
            $borderTravelAction = app(SquadBorderTravelAction::class);
            $borderTravelAction->execute($this->squad, $this->border);

        } catch (SquadTravelException $exception) {

            $squad = $this->squad->fresh();
            $this->assertEquals(SquadTravelException::NOT_ENOUGH_GOLD, $exception->getCode());
            $this->assertEquals($availableGold, $squad->getAvailableGold());
            $this->assertEquals($this->startingProvince->id, $squad->province_id);
            return;
        }

        $this->fail("Exception not thrown");
    }

    public function a_squad_cannot_travel_outside_Fetroya_until_level_10()
    {

    }
}
