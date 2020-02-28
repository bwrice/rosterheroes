<?php

namespace Tests\Feature;

use App\Domain\Actions\Combat\ProcessSideQuestHeroAttack;
use App\Domain\Actions\Combat\SpendResourceCosts;
use App\Domain\Collections\ResourceCostsCollection;
use App\Domain\Combat\Combatants\CombatHero;
use App\Domain\Combat\Combatants\CombatMinion;
use App\Domain\Combat\Attacks\HeroCombatAttack;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\Json\ResourceCosts\FixedResourceCost;
use App\Domain\Models\MeasurableType;
use App\Factories\Combat\CombatHeroFactory;
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

    /** @var CombatHero */
    protected $combatHero;

    public function setUp(): void
    {
        parent::setUp();
        $this->sideQuestResult = SideQuestResultFactory::new()->create();
        $this->heroCombatAttack = HeroCombatAttackFactory::new()->create();
        $this->combatMinion = CombatMinionFactory::new()->create();
        $this->combatHero = CombatHeroFactory::new()->create();
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

        $domainAction->execute($this->sideQuestResult, $moment, $damageReceived, $this->combatHero, $this->heroCombatAttack, $combatMinion, false);

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

        $domainAction->execute($this->sideQuestResult, $moment, $damageReceived, $this->combatHero, $this->heroCombatAttack, $combatMinion, false);

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

        $domainAction->execute($this->sideQuestResult, $moment, $damageReceived, $this->combatHero, $this->heroCombatAttack, $this->combatMinion, true);

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
        $domainAction->execute($this->sideQuestResult, $moment, $damageReceived, $this->combatHero, $this->heroCombatAttack, $combatMinion, false);

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
        $domainAction->execute($this->sideQuestResult, $moment, $damageReceived, $this->combatHero, $this->heroCombatAttack, $combatMinion, false);

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

    /**
     * @test
     * @param $minionHealthReturned
     * @param $block
     * @dataProvider provides_it_save_resource_costs_to_the_side_quest_event
     */
    public function it_save_resource_costs_to_the_side_quest_event($minionHealthReturned, $block)
    {
        $resourceCost = new FixedResourceCost(MeasurableType::STAMINA, 50);
        // Contents of the collection (besides the count) don't matter because we're mocking the return value
        $heroCombatAttack = HeroCombatAttackFactory::new()->withResourceCosts(new ResourceCostsCollection([
            $resourceCost,
            $resourceCost
        ]))->create();

        $staminaCost = rand(5, 20);
        $manaCost = rand(5, 20);

        $spendResourcesMock = \Mockery::mock(SpendResourceCosts::class)
            ->shouldReceive('execute')
            ->times(2)
            ->andReturn([
                'stamina_cost' => $staminaCost,
                'mana_cost' => $manaCost
            ])
            ->getMock();

        app()->instance(SpendResourceCosts::class, $spendResourcesMock);

        /** @var ProcessSideQuestHeroAttack $domainAction */
        $domainAction = app(ProcessSideQuestHeroAttack::class);
        $damageReceived = rand(10, 200);
        $moment = rand(1, 99);

        $combatMinion = \Mockery::mock($this->combatMinion)->shouldReceive('getCurrentHealth')->andReturn($minionHealthReturned)->getMock();
        $sideQuestEvent = $domainAction->execute($this->sideQuestResult, $moment, $damageReceived, $this->combatHero, $heroCombatAttack, $combatMinion, $block);
        $this->assertEquals(2 * $staminaCost, $sideQuestEvent->data['staminaCost']);
        $this->assertEquals(2 * $manaCost, $sideQuestEvent->data['manaCost']);
    }

    public function provides_it_save_resource_costs_to_the_side_quest_event()
    {
        return [
            'block' => [
                'minionHealthReturned' => 100,
                'block' => true
            ],
            'damage' => [
                'minionHealthReturned' => 100,
                'block' => false
            ],
            'kill' => [
                'minionHealthReturned' => 0,
                'block' => false
            ]
        ];
    }

    /**
     * @test
     */
    public function it_will_increase_minion_kills_for_the_hero()
    {
        /** @var ProcessSideQuestHeroAttack $domainAction */
        $domainAction = app(ProcessSideQuestHeroAttack::class);
        $damageReceived = rand(10, 200);
        $moment = rand(1, 99);

        $hero = Hero::findUuid($this->heroCombatAttack->getHeroUuid());
        $this->assertEquals(0, $hero->minion_kills);

        $combatMinion = \Mockery::mock($this->combatMinion)->shouldReceive('getCurrentHealth')->andReturn(0)->getMock();
        $domainAction->execute($this->sideQuestResult, $moment, $damageReceived, $this->combatHero, $this->heroCombatAttack, $combatMinion, false);

        $hero = $hero->fresh();
        $this->assertEquals(1, $hero->minion_kills);
    }

    /**
     * @test
     */
    public function it_will_increase_minion_kills_for_the_item()
    {
        /** @var ProcessSideQuestHeroAttack $domainAction */
        $domainAction = app(ProcessSideQuestHeroAttack::class);
        $damageReceived = rand(10, 200);
        $moment = rand(1, 99);

        $item = Item::findUuid($this->heroCombatAttack->getItemUuid());
        $this->assertEquals(0, $item->minion_kills);

        $combatMinion = \Mockery::mock($this->combatMinion)->shouldReceive('getCurrentHealth')->andReturn(0)->getMock();
        $domainAction->execute($this->sideQuestResult, $moment, $damageReceived, $this->combatHero, $this->heroCombatAttack, $combatMinion, false);

        $item = $item->fresh();
        $this->assertEquals(1, $item->minion_kills);
    }
}
