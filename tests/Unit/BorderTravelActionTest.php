<?php

namespace Tests\Unit;

use App\Domain\Actions\BorderTravelAction;
use App\Domain\Models\Province;
use App\Domain\Models\Squad;
use App\Domain\Services\Travel\BorderTravelCostCalculator;
use App\Exceptions\NotBorderedByException;
use App\Exceptions\NotEnoughGoldException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BorderTravelActionTest extends TestCase
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

        } catch (NotBorderedByException $exception) {

            $squad = $this->squad->fresh();

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
        $availableGold = $this->squad->getAvailableGold();

        $costCalculatorMock = \Mockery::mock(BorderTravelCostCalculator::class);

        // put the mock into the container
        app()->instance(BorderTravelCostCalculator::class, $costCalculatorMock);

        $cost = $availableGold + 1;
        $costCalculatorMock->shouldReceive('goldCost')->andReturn($cost);

        $this->assertFalse($this->squad->borderTravelIsFree($this->border), "Border travel is free");

        try {
            /** @var BorderTravelAction  $borderTravelAction */
            $borderTravelAction = app(BorderTravelAction::class);
            $borderTravelAction->execute($this->squad, $this->border);

        } catch (NotEnoughGoldException $exception) {

            $squad = $this->squad->fresh();
            $this->assertEquals($availableGold, $squad->getAvailableGold());
            $this->assertEquals($this->startingProvince->id, $squad->province_id);
            return;
        }

        $this->fail("Exception not thrown");

    }
}
