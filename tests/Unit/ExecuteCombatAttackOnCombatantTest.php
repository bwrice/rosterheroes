<?php

namespace Tests\Unit;

use App\Domain\Actions\Combat\ExecuteCombatAttackOnCombatant;
use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\Combatants\Combatant;
use App\Domain\Combat\Events\AttackBlocked;
use PHPUnit\Framework\TestCase;

class ExecuteCombatAttackOnCombatantTest extends TestCase
{
    /**
     * @return ExecuteCombatAttackOnCombatant
     */
    protected function getDomainAction()
    {
        return app(ExecuteCombatAttackOnCombatant::class);
    }

    /**
     * @test
     */
    public function it_will_return_a_blocked_event_if_the_combatant_blocks_the_attack()
    {
        $moment = rand(1, 100);
        $combatAttack = \Mockery::mock(CombatAttackInterface::class);
        $combatant = \Mockery::mock(Combatant::class)
            ->shouldReceive('attackBlocked')
            ->andReturn(true)
            ->getMock();

        $event = $this->getDomainAction()->execute($combatAttack, $combatant, $moment);
        $this->assertTrue($event instanceof AttackBlocked);
        $this->assertEquals($moment, $event->moment());
    }
}
