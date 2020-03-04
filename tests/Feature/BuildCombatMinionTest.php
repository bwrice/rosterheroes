<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\BuildCombatAttack;
use App\Domain\Actions\Combat\BuildCombatMinion;
use App\Domain\Actions\Combat\BuildMinionCombatAttack;
use App\Domain\Combat\Combatants\Combatant;
use App\Domain\Combat\Combatants\CombatMinion;
use App\Factories\Combat\AbstractCombatAttackFactory;
use App\Factories\Combat\MinionCombatAttackFactory;
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

        $combatAttack = MinionCombatAttackFactory::new()->create();
        $buildCombatAttackMock = \Mockery::mock(BuildMinionCombatAttack::class)
            ->shouldReceive('execute')
            ->andReturn($combatAttack)
            ->getMock();

        app()->instance(BuildMinionCombatAttack::class, $buildCombatAttackMock);

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
