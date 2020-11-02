<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\VerifyResourcesAvailable;
use App\Domain\Interfaces\SpendsResources;
use App\Domain\Models\Json\ResourceCosts\FixedResourceCost;
use App\Domain\Models\MeasurableType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VerifyResourcesAvailableTest extends TestCase
{
    /**
     * @return VerifyResourcesAvailable
     */
    protected function getDomainAction()
    {
        return app(VerifyResourcesAvailable::class);
    }

    /**
     * @test
     * @param $resourceName
     * @dataProvider provides_resource_cost_names
     */
    public function it_will_return_false_if_not_enough_resources_for_single_resource_cost($resourceName)
    {
        $amountCost = rand(10, 50);
        $resourceCost = new FixedResourceCost($resourceName, $amountCost);

        $spendsResourcesMock = \Mockery::mock(SpendsResources::class)
            ->shouldReceive('getCurrentStamina', 'getCurrentMana')
            ->andReturn($amountCost - 10)
            ->getMock();

        $returnValue = $this->getDomainAction()->execute(collect([$resourceCost]), $spendsResourcesMock);
        $this->assertFalse($returnValue);
    }

    public function provides_resource_cost_names()
    {
        return [
            [
                'resourceName' => MeasurableType::STAMINA
            ],
            [
                'resourceName' => MeasurableType::MANA
            ],
        ];
    }

    /**
     * @test
     * @param $resourceName
     * @dataProvider provides_resource_cost_names
     */
    public function it_will_return_true_if_enough_resources_for_single_resource_cost($resourceName)
    {
        $amountCost = rand(10, 50);
        $resourceCost = new FixedResourceCost($resourceName, $amountCost);

        $spendsResourcesMock = \Mockery::mock(SpendsResources::class)
            ->shouldReceive('getCurrentStamina', 'getCurrentMana')
            ->andReturn($amountCost + 10)
            ->getMock();

        $returnValue = $this->getDomainAction()->execute(collect([$resourceCost]), $spendsResourcesMock);
        $this->assertTrue($returnValue);
    }

    /**
     * @test
     */
    public function it_will_return_false_if_enough_resources_for_one_cost_but_not_another()
    {
        $staminaCost = rand(10, 50);
        $staminaResourceCost = new FixedResourceCost(MeasurableType::STAMINA, $staminaCost);
        $manaCost = rand(10, 50);
        $manaResourceCost = new FixedResourceCost(MeasurableType::MANA, $manaCost);

        $spendsResourcesMock = \Mockery::mock(SpendsResources::class)
            ->shouldReceive('getCurrentStamina')
            ->andReturn($staminaCost + 1)
            ->getMock();

        $spendsResourcesMock->shouldReceive('getCurrentMana')
            ->andReturn($manaCost - 1);

        $returnValue = $this->getDomainAction()->execute(collect([$staminaResourceCost, $manaResourceCost]), $spendsResourcesMock);
        $this->assertFalse($returnValue);
    }
}
