<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\DetermineIfAttackIsBlocked;
use App\Domain\Actions\Combat\ExecuteCombatAttackOnCombatant;
use App\Domain\Combat\Attacks\AdjustDamageForProtection;
use App\Domain\Combat\Attacks\CombatAttackInterface;
use App\Domain\Combat\Combatants\CombatantInterface;
use App\Domain\Combat\Events\AttackBlocked;
use App\Domain\Combat\Events\AttackDamagesCombatant;
use App\Domain\Combat\Events\AttackKillsCombatant;
use App\Domain\Combat\Events\CombatEvent;
use App\Facades\DamageTypeFacade;
use App\Factories\Combat\CombatantFactory;
use App\Factories\Combat\CombatAttackFactory;
use Tests\TestCase;

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
        $attacker = CombatantFactory::new()->create();
        $target = CombatantFactory::new()->create();
        $combatAttack = CombatAttackFactory::new()->create();

        $mock = \Mockery::mock(DetermineIfAttackIsBlocked::class)
            ->shouldReceive('execute')
            ->andReturn(true)
            ->getMock();
        $this->app->instance(DetermineIfAttackIsBlocked::class, $mock);

        $events = $this->getDomainAction()->execute($combatAttack, $attacker, $target, $moment, 1);
        $this->assertEquals(1, $events->count());
        /** @var CombatEvent $event */
        $event = $events->first();
        $this->assertTrue($event instanceof AttackBlocked);
        $this->assertEquals($moment, $event->moment());
    }

    /**
     * @test
     */
    public function it_will_return_a_attack_damages_combatant_event_if_not_blocked_and_combatant_health_above_zero()
    {
        $moment = rand(1, 100);
        $initialDamage = rand(101, 500);
        $damageToReceive = $initialDamage - rand(1, 100);
        $currentHealth = $damageToReceive + rand(1, 1000);
        $attacker = CombatantFactory::new()->create();

        $mock = \Mockery::mock(DetermineIfAttackIsBlocked::class)
            ->shouldReceive('execute')
            ->andReturn(false)
            ->getMock();
        $this->app->instance(DetermineIfAttackIsBlocked::class, $mock);

        DamageTypeFacade::shouldReceive('damagePerTarget')->andReturn($initialDamage);

        $mock = \Mockery::mock(AdjustDamageForProtection::class)
            ->shouldReceive('execute')
            ->andReturn($damageToReceive)
            ->getMock();
        $this->app->instance(AdjustDamageForProtection::class, $mock);

        $combatAttack = CombatAttackFactory::new()->create();

        $target = \Mockery::spy(CombatantInterface::class);
        $target->shouldReceive('getCurrentHealth')->andReturn($currentHealth);

        $events = $this->getDomainAction()->execute($combatAttack, $attacker, $target, $moment, rand(1, 5));

        $target->shouldHaveReceived('updateCurrentHealth')->with($currentHealth - $damageToReceive);

        $this->assertEquals(1, $events->count());
        /** @var AttackDamagesCombatant $event */
        $event = $events->first();
        $this->assertTrue($event instanceof AttackDamagesCombatant);
        $this->assertEquals($damageToReceive, $event->getDamage());
        $this->assertEquals($moment, $event->moment());
    }

    /**
     * @test
     */
    public function it_will_return_a_combatant_killed_event_if_the_damage_received_is_higher_than_the_combatant_current_health()
    {
        $moment = rand(1, 100);
        $initialDamage = rand(101, 500);
        $damageToReceive = $initialDamage - rand(1, 100);
        $currentHealth = $damageToReceive - rand(1, 50);
        $attacker = CombatantFactory::new()->create();

        $mock = \Mockery::mock(DetermineIfAttackIsBlocked::class)
            ->shouldReceive('execute')
            ->andReturn(false)
            ->getMock();
        $this->app->instance(DetermineIfAttackIsBlocked::class, $mock);

        DamageTypeFacade::shouldReceive('damagePerTarget')->andReturn($initialDamage);

        $mock = \Mockery::mock(AdjustDamageForProtection::class)
            ->shouldReceive('execute')
            ->andReturn($damageToReceive)
            ->getMock();
        $this->app->instance(AdjustDamageForProtection::class, $mock);

        $combatAttack = CombatAttackFactory::new()->create();


        $target = \Mockery::spy(CombatantInterface::class);
        $target->shouldReceive('getCurrentHealth')->andReturn($currentHealth);

        $events = $this->getDomainAction()->execute($combatAttack, $attacker, $target, $moment, rand(1, 5));

        $target->shouldHaveReceived('updateCurrentHealth')->with(0);

        $this->assertEquals(2, $events->count());

        /** @var AttackKillsCombatant $killEvent */
        $damageEvent = $events->first(function (CombatEvent $event) use ($moment, $currentHealth) {
            return $event instanceof AttackDamagesCombatant;
        });
        $this->assertEquals($moment, $damageEvent->moment());
        $this->assertEquals($currentHealth, $damageEvent->getDamage());

        /** @var AttackKillsCombatant $killEvent */
        $killEvent = $events->first(function (CombatEvent $event) use ($moment, $currentHealth) {
            return $event instanceof AttackKillsCombatant;
        });

        $this->assertEquals($moment, $killEvent->moment());
    }
}
