<?php

namespace Tests\Feature;

use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Combat\Attacks\CombatAttackDataMapper;
use App\Domain\Combat\Attacks\HeroCombatAttackDataMapper;
use App\Domain\Models\Json\ResourceCosts\FixedResourceCost;
use App\Domain\Models\Json\ResourceCosts\PercentResourceCost;
use App\Domain\Models\MeasurableType;
use App\Factories\Combat\CombatAttackFactory;
use App\Factories\Combat\HeroCombatAttackFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HeroCombatAttackDataMapperTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_map_the_data_array_into_a_hero_combat_attack()
    {
        $resourceCosts = new ResourceCostsCollection([
            new FixedResourceCost(MeasurableType::STAMINA, 15),
            new PercentResourceCost(MeasurableType::MANA, 5)
        ]);
        $combatAttack = CombatAttackFactory::new()->create();
        $originalHeroCombatAttack = HeroCombatAttackFactory::new()->withResourceCosts($resourceCosts)->withCombatAttack($combatAttack)->create();

        $combatAttackMapperMock = \Mockery::mock(CombatAttackDataMapper::class)
            ->shouldReceive('getCombatAttack')
            ->andReturn($combatAttack)
            ->getMock();

        app()->instance(CombatAttackDataMapper::class, $combatAttackMapperMock);

        /** @var HeroCombatAttackDataMapper $mapper */
        $mapper = app(HeroCombatAttackDataMapper::class);
        $heroCombatAttack = $mapper->getHeroCombatAttack($originalHeroCombatAttack->toArray());
        $this->assertTrue($originalHeroCombatAttack == $heroCombatAttack);
    }
}
