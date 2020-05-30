<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\ProcessSideQuestMinionAttack;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Models\Hero;
use App\Factories\Combat\CombatHeroFactory;
use App\Factories\Combat\CombatMinionFactory;
use App\Factories\Combat\MinionCombatAttackFactory;
use App\Factories\Models\SideQuestResultFactory;
use App\Domain\Models\SideQuestEvent;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProcessSideQuestMinionAttackTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var \App\Domain\Models\SideQuestResult
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
     * @dataProvider provides_it_will_properly_return_side_quest_events_for_minion_attacks_hero_events
     */
    public function it_will_properly_return_side_quest_events_for_minion_attacks_hero_events($heroCurrentHealth, $block, $eventType)
    {

        $combatHeroMock = \Mockery::mock($this->combatHero)->shouldReceive('getCurrentHealth')->andReturn($heroCurrentHealth)->getMock();
        /** @var ProcessSideQuestMinionAttack $domainAction */
        $domainAction = app(ProcessSideQuestMinionAttack::class);
        $sideQuestEvent = $domainAction->execute($this->moment, $this->damageReceived, $this->combatMinion, $this->minionCombatAttack, $combatHeroMock, $block);

        $this->assertEquals($this->moment, $sideQuestEvent->moment);
        $this->assertEquals($eventType, $sideQuestEvent->event_type);
        $this->assertEquals($this->combatHero->getHeroUuid(), $sideQuestEvent->data['combatHero']['heroUuid']);
        $this->assertEquals($this->combatMinion->getMinionUuid(), $sideQuestEvent->data['combatMinion']['minionUuid']);
        $this->assertEquals($this->minionCombatAttack->getAttackUuid(), $sideQuestEvent->data['minionCombatAttack']['attackUuid']);
        if (!$block) {
            $this->assertEquals($this->damageReceived, $sideQuestEvent->data['damage']);
        }
    }

    public function provides_it_will_properly_return_side_quest_events_for_minion_attacks_hero_events()
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
     * @param $heroCurrentHealth
     * @dataProvider provides_it_will_increase_damages_received_for_the_combat_hero
     */
    public function it_will_increase_damages_received_for_the_combat_hero($heroCurrentHealth)
    {
        /** @var ProcessSideQuestMinionAttack $domainAction */
        $domainAction = app(ProcessSideQuestMinionAttack::class);

        // Mock hero survives
        /** @var CombatHero $combatHeroMock */
        $combatHeroMock = \Mockery::mock($this->combatHero)->shouldReceive('getCurrentHealth')->andReturn($heroCurrentHealth)->getMock();
        $domainAction->execute($this->moment, $this->damageReceived, $this->combatMinion, $this->minionCombatAttack, $combatHeroMock, false);
        $this->assertEquals(1, count($combatHeroMock->getDamagesReceived()));
        $this->assertEquals($this->damageReceived, $combatHeroMock->getDamagesReceived()[0]);
    }

    public function provides_it_will_increase_damages_received_for_the_combat_hero()
    {
        return [
            'survived' => [
                'heroCurrentHealth' => 100,
            ],
            'killed' => [
                'heroCurrentHealth' => 0,
            ]
        ];
    }

    /**
     * @test
     */
    public function it_will_increase_the_blocks_on_the_combat_hero()
    {
        /** @var ProcessSideQuestMinionAttack $domainAction */
        $domainAction = app(ProcessSideQuestMinionAttack::class);

        // Mock hero survives
        /** @var CombatHero $combatHeroMock */
        $combatHeroMock = \Mockery::mock($this->combatHero)->shouldReceive('getCurrentHealth')->andReturn(100)->getMock();
        $domainAction->execute($this->moment, $this->damageReceived, $this->combatMinion, $this->minionCombatAttack, $combatHeroMock, true);
        $this->assertEquals(1, $combatHeroMock->getBlocks());
        $this->assertEquals(0, count($combatHeroMock->getDamagesReceived()));
    }

}
