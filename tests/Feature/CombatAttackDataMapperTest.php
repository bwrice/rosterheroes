<?php

namespace Tests\Feature;

use App\Domain\Combat\Attacks\CombatAttackDataMapper;
use App\Factories\Combat\CombatAttackFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CombatAttackDataMapperTest extends TestCase
{
    /**
     * @test
     */
    public function it_will_return_a_matching_combat_attack_from_a_combat_attack_converted_to_an_array()
    {
        $originalCombatAttack = CombatAttackFactory::new()->create();

        /** @var CombatAttackDataMapper $mapper */
        $mapper = app(CombatAttackDataMapper::class);
        $combatAttack = $mapper->getCombatAttack($originalCombatAttack->toArray());
        $this->assertTrue($originalCombatAttack == $combatAttack);
    }
}
