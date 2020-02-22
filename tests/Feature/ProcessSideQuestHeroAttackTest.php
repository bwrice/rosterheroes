<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\ProcessSideQuestHeroAttack;
use App\Domain\Combat\CombatMinion;
use App\Domain\Combat\Attacks\HeroCombatAttack;
use App\Factories\Combat\CombatMinionFactory;
use App\Factories\Combat\HeroCombatAttackFactory;
use App\Factories\Models\SideQuestFactory;
use App\Factories\Models\SideQuestResultFactory;
use App\SideQuestEvent;
use App\SideQuestResult;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProcessSideQuestHeroAttackTest extends TestCase
{
    use DatabaseTransactions;

    /** @var SideQuestResult */
    protected $sideQuestResult;

    /** @var HeroCombatAttack */
    protected $heroCombatAttack;

    /** @var CombatMinion */
    protected $combatMinion;

    public function setUp(): void
    {
        parent::setUp();
        $this->sideQuestResult = SideQuestResultFactory::new()->create();
        $this->heroCombatAttack = HeroCombatAttackFactory::new()->create();
        $this->combatMinion = CombatMinionFactory::new()->create();
    }

    /**
     * @test
     */
    public function it_will_save_a_hero_attack_event_for_the_side_quest_result()
    {
        /** @var ProcessSideQuestHeroAttack $domainAction */
        $domainAction = app(ProcessSideQuestHeroAttack::class);
        $damageReceived = rand(10, 200);
        $moment = rand(1, 99);
        $domainAction->execute($this->sideQuestResult, $moment, $damageReceived, $this->heroCombatAttack, $this->combatMinion, false);
        $sideQuestEvents = $this->sideQuestResult->sideQuestEvents;
        $this->assertEquals(1, $sideQuestEvents->count());
        /** @var SideQuestEvent $event */
        $event = $sideQuestEvents->first();
        $this->assertEquals($moment, $event->moment);
        $this->assertEquals('hero-attack', $event->data['type']);
    }

    /**
     * @test
     */
    public function it_will_correctly_set_the_kill_attribute()
    {
        /** @var ProcessSideQuestHeroAttack $domainAction */
        $domainAction = app(ProcessSideQuestHeroAttack::class);
        $damageReceived = rand(10, 200);
        $moment = rand(1, 99);

        $combatMinion = \Mockery::mock($this->combatMinion)->shouldReceive('getCurrentHealth')->andReturn(999)->getMock();
        $sideQuestEvent = $domainAction->execute($this->sideQuestResult, $moment, $damageReceived, $this->heroCombatAttack, $combatMinion, false);
        $this->assertFalse($sideQuestEvent->data['kill']);

        $combatMinion = \Mockery::mock($this->combatMinion)->shouldReceive('getCurrentHealth')->andReturn(0)->getMock();
        $sideQuestEvent = $domainAction->execute($this->sideQuestResult, $moment, $damageReceived, $this->heroCombatAttack, $combatMinion, false);
        $this->assertTrue($sideQuestEvent->data['kill']);

        // With "block" as true
        $combatMinion = \Mockery::mock($this->combatMinion)->shouldReceive('getCurrentHealth')->andReturn(0)->getMock();
        $sideQuestEvent = $domainAction->execute($this->sideQuestResult, $moment, $damageReceived, $this->heroCombatAttack, $combatMinion, true);
        $this->assertFalse($sideQuestEvent->data['kill']);
    }

    /**
     * @test
     */
    public function it_will_correctly_set_the_block_attribute()
    {

        /** @var ProcessSideQuestHeroAttack $domainAction */
        $domainAction = app(ProcessSideQuestHeroAttack::class);
        $damageReceived = rand(10, 200);
        $moment = rand(1, 99);

        $sideQuestEvent = $domainAction->execute($this->sideQuestResult, $moment, $damageReceived, $this->heroCombatAttack, $this->combatMinion, false);
        $this->assertFalse($sideQuestEvent->data['block']);

        $sideQuestEvent = $domainAction->execute($this->sideQuestResult, $moment, $damageReceived, $this->heroCombatAttack, $this->combatMinion, true);
        $this->assertTrue($sideQuestEvent->data['block']);
    }
}
