<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\ProcessSideQuestMinionAttack;
use App\Domain\Models\Hero;
use App\Factories\Combat\CombatHeroFactory;
use App\Factories\Combat\CombatMinionFactory;
use App\Factories\Combat\MinionCombatAttackFactory;
use App\Factories\Models\SideQuestResultFactory;
use App\SideQuestEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProcessSideQuestMinionAttackTest extends TestCase
{
    /**
     * @var \App\SideQuestResult
     */
    protected $sideQuestResult;
    /**
     * @var \App\Domain\Combat\Combatants\CombatMinion
     */
    protected $combatMinion;
    /**
     * @var \App\Domain\Combat\Attacks\MinionCombatAttack
     */
    protected $minionCombatAttack;
    /**
     * @var \App\Domain\Combat\Combatants\AbstractCombatant|\App\Domain\Combat\Combatants\CombatHero
     */
    protected $combatHero;
    /**
     * @var int
     */
    protected $damageReceived;
    /**
     * @var int
     */
    protected $moment;

    public function setUp(): void
    {
        parent::setUp();

        $this->sideQuestResult = SideQuestResultFactory::new()->create();
        $this->combatMinion = CombatMinionFactory::new()->create();
        $this->minionCombatAttack = MinionCombatAttackFactory::new()->create();
        $this->combatHero = CombatHeroFactory::new()->create();
        $this->damageReceived = rand(10, 100);
        $this->moment = rand(1, 99);
    }

    /**
     * @test
     * @param $heroCurrentHealth
     * @param $block
     * @param $eventType
     * @dataProvider provides_it_will_properly_save_side_quest_events_for_minion_attacks_hero_events
     */
    public function it_will_properly_save_side_quest_events_for_minion_attacks_hero_events($heroCurrentHealth, $block, $eventType)
    {

        $combatHeroMock = \Mockery::mock($this->combatHero)->shouldReceive('getCurrentHealth')->andReturn($heroCurrentHealth)->getMock();
        /** @var ProcessSideQuestMinionAttack $domainAction */
        $domainAction = app(ProcessSideQuestMinionAttack::class);
        $domainAction->execute($this->sideQuestResult, $this->moment, $this->damageReceived, $this->combatMinion, $this->minionCombatAttack, $combatHeroMock, $block);

        $sideQuestEvents = $this->sideQuestResult->sideQuestEvents;
        $this->assertEquals(1, $sideQuestEvents->count());
        /** @var SideQuestEvent $sideQuestEvent */
        $sideQuestEvent = $sideQuestEvents->first();
        $this->assertEquals($this->moment, $sideQuestEvent->moment);
        $this->assertEquals($eventType, $sideQuestEvent->event_type);
        $this->assertEquals($this->combatHero->getHeroUuid(), $sideQuestEvent->data['combatHero']['heroUuid']);
        $this->assertEquals($this->combatMinion->getMinionUuid(), $sideQuestEvent->data['combatMinion']['minionUuid']);
        $this->assertEquals($this->minionCombatAttack->getAttackUuid(), $sideQuestEvent->data['minionCombatAttack']['attackUuid']);
        if (!$block) {
            $this->assertEquals($this->damageReceived, $sideQuestEvent->data['damage']);
        }
    }

    public function provides_it_will_properly_save_side_quest_events_for_minion_attacks_hero_events()
    {
        return [
            'block' => [
                'heroCurrentHealth' => 100,
                'block' => true,
                'eventType' => SideQuestEvent::TYPE_HERO_BLOCKS_MINION
            ],
            'damage' => [
                'heroCurrentHealth' => 100,
                'block' => false,
                'eventType' => SideQuestEvent::TYPE_MINION_DAMAGES_HERO
            ],
            'kill' => [
                'heroCurrentHealth' => 0,
                'block' => false,
                'eventType' => SideQuestEvent::TYPE_MINION_KILLS_HERO
            ]
        ];
    }

    /**
     * @test
     */
    public function it_will_increase_damage_taken_for_a_hero()
    {
        $hero = Hero::findUuidOrFail($this->combatHero->getHeroUuid());
        $previousDamageTaken = $hero->damage_taken;
        /** @var ProcessSideQuestMinionAttack $domainAction */
        $domainAction = app(ProcessSideQuestMinionAttack::class);

        // Mock hero survives
        $combatHeroMock = \Mockery::mock($this->combatHero)->shouldReceive('getCurrentHealth')->andReturn(99)->getMock();
        $domainAction->execute($this->sideQuestResult, $this->moment, $this->damageReceived, $this->combatMinion, $this->minionCombatAttack, $combatHeroMock, false);

        $hero = $hero->fresh();
        $newDamage = $hero->damage_taken;
        $this->assertEquals($previousDamageTaken + $this->damageReceived, $newDamage);

        $previousDamageTaken = $newDamage;

        // Mock hero killed
        $combatHeroMock = \Mockery::mock($this->combatHero)->shouldReceive('getCurrentHealth')->andReturn(0)->getMock();
        $domainAction->execute($this->sideQuestResult, $this->moment, $this->damageReceived, $this->combatMinion, $this->minionCombatAttack, $combatHeroMock, false);

        $hero = $hero->fresh();
        $newDamage = $hero->damage_taken;
        $this->assertEquals($previousDamageTaken + $this->damageReceived, $newDamage);
    }

}
