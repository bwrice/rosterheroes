<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\ProcessSideQuestHeroAttack;
use App\Domain\Combat\Combatants\CombatMinion;
use App\Domain\Combat\Attacks\HeroCombatAttack;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Factories\Combat\CombatMinionFactory;
use App\Factories\Combat\HeroCombatAttackFactory;
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
    public function it_will_save_a_hero_damages_minion_event_for_the_side_quest_result()
    {
        /** @var ProcessSideQuestHeroAttack $domainAction */
        $domainAction = app(ProcessSideQuestHeroAttack::class);
        $damageReceived = rand(10, 200);
        $moment = rand(1, 99);
        $combatMinion = \Mockery::mock($this->combatMinion)->shouldReceive('getCurrentHealth')->andReturn(999)->getMock();

        $domainAction->execute($this->sideQuestResult, $moment, $damageReceived, $this->heroCombatAttack, $combatMinion, false);

        $sideQuestEvents = $this->sideQuestResult->sideQuestEvents;
        $this->assertEquals(1, $sideQuestEvents->count());
        /** @var SideQuestEvent $event */
        $event = $sideQuestEvents->first();
        $this->assertEquals($moment, $event->moment);
        $this->assertEquals(SideQuestEvent::TYPE_HERO_DAMAGES_MINION, $event->event_type);
    }

    /**
     * @test
     */
    public function it_will_save_a_hero_kills_minion_event_for_the_side_quest_result()
    {
        /** @var ProcessSideQuestHeroAttack $domainAction */
        $domainAction = app(ProcessSideQuestHeroAttack::class);
        $damageReceived = rand(10, 200);
        $moment = rand(1, 99);
        $combatMinion = \Mockery::mock($this->combatMinion)->shouldReceive('getCurrentHealth')->andReturn(0)->getMock();

        $domainAction->execute($this->sideQuestResult, $moment, $damageReceived, $this->heroCombatAttack, $combatMinion, false);

        $sideQuestEvents = $this->sideQuestResult->sideQuestEvents;
        $this->assertEquals(1, $sideQuestEvents->count());
        /** @var SideQuestEvent $event */
        $event = $sideQuestEvents->first();
        $this->assertEquals($moment, $event->moment);
        $this->assertEquals(SideQuestEvent::TYPE_HERO_KILLS_MINION, $event->event_type);
    }

    /**
     * @test
     */
    public function it_will_save_a_minion_blocks_hero_event_for_the_side_quest_result()
    {
        /** @var ProcessSideQuestHeroAttack $domainAction */
        $domainAction = app(ProcessSideQuestHeroAttack::class);
        $damageReceived = rand(10, 200);
        $moment = rand(1, 99);

        $domainAction->execute($this->sideQuestResult, $moment, $damageReceived, $this->heroCombatAttack, $this->combatMinion, true);

        $sideQuestEvents = $this->sideQuestResult->sideQuestEvents;
        $this->assertEquals(1, $sideQuestEvents->count());
        /** @var SideQuestEvent $event */
        $event = $sideQuestEvents->first();
        $this->assertEquals($moment, $event->moment);
        $this->assertEquals(SideQuestEvent::TYPE_MINION_BLOCKS_HERO, $event->event_type);
    }

    /**
     * @test
     * @param $minionHealthReturned
     * @dataProvider provides_damage_dealt_tests
     */
    public function it_will_increase_the_damage_dealt_on_the_item($minionHealthReturned)
    {
        /** @var ProcessSideQuestHeroAttack $domainAction */
        $domainAction = app(ProcessSideQuestHeroAttack::class);
        $damageReceived = rand(10, 200);
        $moment = rand(1, 99);

        $item = Item::findUuid($this->heroCombatAttack->getItemUuid());
        $this->assertEquals(0, $item->damage_dealt);

        $combatMinion = \Mockery::mock($this->combatMinion)->shouldReceive('getCurrentHealth')->andReturn($minionHealthReturned)->getMock();
        $domainAction->execute($this->sideQuestResult, $moment, $damageReceived, $this->heroCombatAttack, $combatMinion, false);

        $item = $item->fresh();
        $this->assertEquals($damageReceived, $item->damage_dealt);
    }

    /**
     * @test
     * @param $minionHealthReturned
     * @dataProvider provides_damage_dealt_tests
     */
    public function it_will_increase_damage_dealt_for_the_hero($minionHealthReturned)
    {
        /** @var ProcessSideQuestHeroAttack $domainAction */
        $domainAction = app(ProcessSideQuestHeroAttack::class);
        $damageReceived = rand(10, 200);
        $moment = rand(1, 99);

        $hero = Hero::findUuid($this->heroCombatAttack->getHeroUuid());
        $this->assertEquals(0, $hero->damage_dealt);

        $combatMinion = \Mockery::mock($this->combatMinion)->shouldReceive('getCurrentHealth')->andReturn($minionHealthReturned)->getMock();
        $domainAction->execute($this->sideQuestResult, $moment, $damageReceived, $this->heroCombatAttack, $combatMinion, false);

        $hero = $hero->fresh();
        $this->assertEquals($damageReceived, $hero->damage_dealt);
    }

    public function provides_damage_dealt_tests()
    {
        return [
            'damage' => [
                'minionHealthReturned' => 100
            ],
            'kill' => [
                'minionHealthReturned' => 0
            ]
        ];
    }
}
