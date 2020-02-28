<?php

namespace Tests\Feature;

use App\Domain\Combat\Attacks\CombatAttackDataMapper;
use App\Domain\Combat\Attacks\HeroCombatAttackDataMapper;
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
        $combatAttack = CombatAttackFactory::new()->create();
        $originalHeroCombatAttack = HeroCombatAttackFactory::new()->withCombatAttack($combatAttack)->create();

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
