<?php

namespace Tests\Unit;

use App\Domain\Actions\BorderTravelAction;
use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use App\Domain\Services\Travel\BorderTravelCostCalculator;
use App\Domain\Services\Travel\SquadBorderTravelCostExemption;
use App\Exceptions\BorderTravelException;
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
         * Force Squad to have no travel cost exemption
         */
        $exemptionMock = \Mockery::mock(SquadBorderTravelCostExemption::class);
        $exemptionMock->shouldReceive('isExempt')->andReturn(false);
        // put the mock into the container
        app()->instance(SquadBorderTravelCostExemption::class, $exemptionMock);

        $this->assertFalse($this->squad->hasBorderTravelCostExemption($this->border), "Border travel is free");

        /*
         * Force travel cost to be less than available gold
         */
        $availableGold = $this->squad->getAvailableGold();
        $this->assertGreaterThan(0, $availableGold);
        $cost = $availableGold - 1;
        $costCalculatorMock = \Mockery::mock(BorderTravelCostCalculator::class);
        $costCalculatorMock->shouldReceive('goldCost')->andReturn($cost);
        // put the mock into the container
        app()->instance(BorderTravelCostCalculator::class, $costCalculatorMock);

        /** @var BorderTravelAction  $borderTravelAction */
        $borderTravelAction = app(BorderTravelAction::class);
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
            /** @var BorderTravelAction  $borderTravelAction */
            $borderTravelAction = app(BorderTravelAction::class);
            $borderTravelAction->execute($this->squad, $nonBorder);

        } catch (BorderTravelException $exception) {

            $squad = $this->squad->fresh();
            $this->assertEquals(BorderTravelException::NOT_BORDERED_BY, $exception->getCode());
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
         * Force Squad to have no travel cost exemption
         */
        $exemptionMock = \Mockery::mock(SquadBorderTravelCostExemption::class);
        $exemptionMock->shouldReceive('isExempt')->andReturn(false);
        // put the mock into the container
        app()->instance(SquadBorderTravelCostExemption::class, $exemptionMock);

        $this->assertFalse($this->squad->hasBorderTravelCostExemption($this->border), "Border travel is free");

        /*
         * Force travel cost to be too expensive
         */
        $availableGold = $this->squad->getAvailableGold();
        $cost = $availableGold + 1;
        $costCalculatorMock = \Mockery::mock(BorderTravelCostCalculator::class);
        $costCalculatorMock->shouldReceive('goldCost')->andReturn($cost);
        // put the mock into the container
        app()->instance(BorderTravelCostCalculator::class, $costCalculatorMock);


        try {
            /** @var BorderTravelAction  $borderTravelAction */
            $borderTravelAction = app(BorderTravelAction::class);
            $borderTravelAction->execute($this->squad, $this->border);

        } catch (BorderTravelException $exception) {

            $squad = $this->squad->fresh();
            $this->assertEquals(BorderTravelException::NOT_ENOUGH_GOLD, $exception->getCode());
            $this->assertEquals($availableGold, $squad->getAvailableGold());
            $this->assertEquals($this->startingProvince->id, $squad->province_id);
            return;
        }

        $this->fail("Exception not thrown");
    }
}
