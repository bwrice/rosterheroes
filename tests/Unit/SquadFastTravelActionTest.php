<?php

namespace Tests\Unit;

use App\Domain\Actions\SquadFastTravelAction;
use App\Domain\Collections\ProvinceCollection;
use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use App\Domain\Models\Support\Squads\SquadBorderTravelCostCalculator;
use App\Exceptions\SquadTravelException;
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
         * Force travel cost to be less than available gold
         */
        $availableGold = $this->squad->getAvailableGold();
        $this->assertGreaterThan(0, $availableGold);
        $bordersCount = $this->travelRoute->count();
        $individualBorderCost = (int) round($availableGold/$bordersCount) - 1;
        $costCalculatorMock = \Mockery::mock(SquadBorderTravelCostCalculator::class);
        $costCalculatorMock->shouldReceive('calculateGoldCost')->andReturn($individualBorderCost);
        // put the mock into the container
        app()->instance(SquadBorderTravelCostCalculator::class, $costCalculatorMock);

        /** @var SquadFastTravelAction $fastTravelAction */
        $fastTravelAction = app(SquadFastTravelAction::class);
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
         * Force travel cost to be less than available gold
         */
        $availableGold = $this->squad->getAvailableGold();
        $this->assertGreaterThan(0, $availableGold);
        $bordersCount = $this->travelRoute->count();
        $individualBorderCost = (int) round($availableGold/$bordersCount) - 1;
        $costCalculatorMock = \Mockery::mock(SquadBorderTravelCostCalculator::class);
        $costCalculatorMock->shouldReceive('calculateGoldCost')->andReturn($individualBorderCost);
        // put the mock into the container
        app()->instance(SquadBorderTravelCostCalculator::class, $costCalculatorMock);

        try {

            /** @var SquadFastTravelAction $fastTravelAction */
            $fastTravelAction = app(SquadFastTravelAction::class);
            $fastTravelAction->execute($this->squad, $this->travelRoute);

        } catch (SquadTravelException $exception) {

            $squad = $this->squad->fresh();

            $this->assertEquals(SquadTravelException::NOT_BORDERED_BY, $exception->getCode());
            $this->assertEquals($nonBorder->id, $exception->getBorder()->id);
            $this->assertEquals($this->startingProvince->id, $squad->province_id, "Squad never relocated");
            $this->assertEquals($availableGold, $squad->getAvailableGold(), "No gold was spent");
            return;
        }

        $this->fail("Exception Not Thrown");
    }
}
