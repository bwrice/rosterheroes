<?php

namespace Tests\Unit;

use App\Domain\Actions\FastTravelAction;
use App\Domain\Collections\ProvinceCollection;
use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use App\Domain\Services\Travel\CalculateBorderTravelCostForSquadAction;
use App\Domain\Services\Travel\SquadBorderTravelCostExemption;
use App\Exceptions\BorderTravelException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SquadFastTravelActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Squad */
    protected $squad;

    /** @var Province */
    protected $startingProvince;

    /** @var ProvinceCollection */
    protected $travelRoute;

    public function setUp(): void
    {
        parent::setUp();

        $this->squad = factory(Squad::class)->create();
        $this->startingProvince = $this->squad->province;

        /*
         * Build a route to fast travel through
         */
        $travelRoute = new ProvinceCollection();
        $currentLocation = $this->startingProvince;
        $excludeProvinceIDs[] = $currentLocation->id;
        foreach(range(1,3) as $routeNumber) {
            $border = $currentLocation->borders()
                ->whereNotIn('id', $excludeProvinceIDs)
                ->inRandomOrder()->first();
            $travelRoute->push($border);
            $currentLocation = $border;
        }

        $this->travelRoute = $travelRoute;
    }

    /**
     * @test
     */
    public function a_squad_can_fast_travel_through_multiple_provinces()
    {
        /*
         * Force Squad to have no travel cost exemption
         */
        $exemptionMock = \Mockery::mock(SquadBorderTravelCostExemption::class);
        $exemptionMock->shouldReceive('isExempt')->andReturn(false);
        // put the mock into the container
        app()->instance(SquadBorderTravelCostExemption::class, $exemptionMock);

        $this->travelRoute->each(function(Province $border) {
            $this->assertFalse($this->squad->hasBorderTravelCostExemption($border), "Border travel is free");
        });

        /*
         * Force travel cost to be less than available gold
         */
        $availableGold = $this->squad->getAvailableGold();
        $this->assertGreaterThan(0, $availableGold);
        $bordersCount = $this->travelRoute->count();
        $individualBorderCost = (int) round($availableGold/$bordersCount) - 1;
        $costCalculatorMock = \Mockery::mock(CalculateBorderTravelCostForSquadAction::class);
        $costCalculatorMock->shouldReceive('goldCost')->andReturn($individualBorderCost);
        // put the mock into the container
        app()->instance(CalculateBorderTravelCostForSquadAction::class, $costCalculatorMock);

        /** @var FastTravelAction $fastTravelAction */
        $fastTravelAction = app(FastTravelAction::class);
        $fastTravelAction->execute($this->squad, $this->travelRoute);

        $squad = $this->squad->fresh();
        $expectedGold = $availableGold - ($individualBorderCost * $bordersCount);
        $this->assertEquals($expectedGold, $squad->getAvailableGold());
        $this->assertEquals($this->travelRoute->last()->id, $squad->province_id);
    }

    /**
     * @test
     */
    public function it_will_roll_back_everything_if_the_entire_route_cant_be_finished()
    {
        $lastLocation = $this->travelRoute->last();
        $nonBorder = Province::query()->whereDoesntHave('borders', function (Builder $builder) use ($lastLocation) {
            return $builder->where('id', '=', $lastLocation->id);
        })->inRandomOrder()->first();

        $this->travelRoute->push($nonBorder);

        /*
         * Force Squad to have no travel cost exemption
         */
        $exemptionMock = \Mockery::mock(SquadBorderTravelCostExemption::class);
        $exemptionMock->shouldReceive('isExempt')->andReturn(false);
        // put the mock into the container
        app()->instance(SquadBorderTravelCostExemption::class, $exemptionMock);

        $this->travelRoute->each(function(Province $border) {
            $this->assertFalse($this->squad->hasBorderTravelCostExemption($border), "Border travel is free");
        });

        /*
         * Force travel cost to be less than available gold
         */
        $availableGold = $this->squad->getAvailableGold();
        $this->assertGreaterThan(0, $availableGold);
        $bordersCount = $this->travelRoute->count();
        $individualBorderCost = (int) round($availableGold/$bordersCount) - 1;
        $costCalculatorMock = \Mockery::mock(CalculateBorderTravelCostForSquadAction::class);
        $costCalculatorMock->shouldReceive('goldCost')->andReturn($individualBorderCost);
        // put the mock into the container
        app()->instance(CalculateBorderTravelCostForSquadAction::class, $costCalculatorMock);

        try {

            /** @var FastTravelAction $fastTravelAction */
            $fastTravelAction = app(FastTravelAction::class);
            $fastTravelAction->execute($this->squad, $this->travelRoute);

        } catch (BorderTravelException $exception) {

            $squad = $this->squad->fresh();

            $this->assertEquals(BorderTravelException::NOT_BORDERED_BY, $exception->getCode());
            $this->assertEquals($nonBorder->id, $exception->getBorder()->id);
            $this->assertEquals($this->startingProvince->id, $squad->province_id, "Squad never relocated");
            $this->assertEquals($availableGold, $squad->getAvailableGold(), "No gold was spent");
            return;
        }

        $this->fail("Exception Not Thrown");
    }
}
