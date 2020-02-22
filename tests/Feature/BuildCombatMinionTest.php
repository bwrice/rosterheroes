<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\BuildCombatAttack;
use App\Domain\Actions\Combat\BuildCombatMinion;
use App\Domain\Combat\Combatants\Combatant;
use App\Domain\Combat\Combatants\CombatMinion;
use App\Factories\Combat\CombatAttackFactory;
use App\Factories\Models\MinionFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BuildCombatMinionTest extends TestCase
{
    /**
     * @test
     */
    public function it_will_correctly_build_a_combat_minion()
    {
        $minion = MinionFactory::new()->withAttacks()->create();

        $combatAttack = CombatAttackFactory::new()->create();
        $buildCombatAttackMock = \Mockery::mock(BuildCombatAttack::class)
            ->shouldReceive('execute')
            ->andReturn($combatAttack)
            ->getMock();

        app()->instance(BuildCombatAttack::class, $buildCombatAttackMock);

        /** @var BuildCombatMinion $domainAction */
        $domainAction = app(BuildCombatMinion::class);
        $combatMinion = $domainAction->execute($minion);
        $this->assertTrue($combatMinion instanceof CombatMinion);
        $this->assertTrue($combatMinion instanceof Combatant);
        $minionAttacksCount = $minion->attacks->count();
        $this->assertGreaterThan(0, $minionAttacksCount);
        $this->assertEquals($minionAttacksCount, $combatMinion->getCombatAttacks()->count());
    }
}
