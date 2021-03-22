<?php

namespace Tests\Feature;

use App\Domain\Actions\BuildFastTravelRoute;
use App\Domain\Actions\FindPathBetweenProvinces;
use App\Domain\Models\Province;
use App\Domain\Models\Support\Squads\SquadBorderTravelCostCalculator;
use App\Factories\Models\SquadFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BuildFastTravelRouteTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return BuildFastTravelRoute
     */
    protected function getDomainAction()
    {
        return app(BuildFastTravelRoute::class);
    }

    /**
     * @test
     */
    public function it_will_return_a_collection_of_provinces_with_costs_of_travel()
    {
        $squad = SquadFactory::new()->create();

        $provinces = Province::query()->inRandomOrder()->take(rand(2, 5))->get();
        $pathFinderMock = \Mockery::mock(FindPathBetweenProvinces::class)
            ->shouldReceive('execute')
            ->andReturn($provinces)
            ->getMock();

        $this->app->instance(FindPathBetweenProvinces::class, $pathFinderMock);

        $costAmounts = $provinces->map(function () {
            return rand(1, 99);
        });

        $calculatorMock = \Mockery::mock(SquadBorderTravelCostCalculator::class)
            ->shouldReceive('calculateGoldCost')
            ->andReturn(...$costAmounts->toArray())
            ->getMock();

        $this->app->instance(SquadBorderTravelCostCalculator::class, $calculatorMock);

        $fastTravelRoute = $this->getDomainAction()->execute($squad, $provinces->last());
        $this->assertEquals($provinces->count(), $fastTravelRoute->count());
        
        /*
         * Loop through returned route and verify each province and cost matches the mocked
         * returns of the action dependencies
         */
        $fastTravelRoute->each(function ($routeStop) use ($provinces, $costAmounts) {
            $this->assertEquals($routeStop['province']->id, $provinces->shift()->id);
            $this->assertEquals($routeStop['cost'], $costAmounts->shift());
        });
    }
}
