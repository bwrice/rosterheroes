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
    public function it_will_return_a_hero_damages_minion_event_for_the_side_quest_result()
    {
        /** @var ProcessSideQuestHeroAttack $domainAction */
        $domainAction = app(ProcessSideQuestHeroAttack::class);
        $damageReceived = rand(10, 200);
        $moment = rand(1, 99);
        $combatMinion = \Mockery::mock($this->combatMinion)->shouldReceive('getCurrentHealth')->andReturn(999)->getMock();

        $sideQuestEvent = $domainAction->execute($moment, $damageReceived, $this->combatHero, $this->heroCombatAttack, $combatMinion, false);

        $this->assertEquals($moment, $sideQuestEvent->moment);
        $this->assertEquals(SideQuestEvent::TYPE_HERO_DAMAGES_MINION, $sideQuestEvent->event_type);
    }

    /**
     * @test
     */
    public function it_will_return_a_hero_kills_minion_event_for_the_side_quest_result()
    {
        /** @var ProcessSideQuestHeroAttack $domainAction */
        $domainAction = app(ProcessSideQuestHeroAttack::class);
        $damageReceived = rand(10, 200);
        $moment = rand(1, 99);
        $combatMinion = \Mockery::mock($this->combatMinion)->shouldReceive('getCurrentHealth')->andReturn(0)->getMock();

        $sideQuestEvent = $domainAction->execute($moment, $damageReceived, $this->combatHero, $this->heroCombatAttack, $combatMinion, false);

        $this->assertEquals($moment, $sideQuestEvent->moment);
        $this->assertEquals(SideQuestEvent::TYPE_HERO_KILLS_MINION, $sideQuestEvent->event_type);
    }

    /**
     * @test
     */
    public function it_will_return_a_minion_blocks_hero_event_for_the_side_quest_result()
    {
        /** @var ProcessSideQuestHeroAttack $domainAction */
        $domainAction = app(ProcessSideQuestHeroAttack::class);
        $damageReceived = rand(10, 200);
        $moment = rand(1, 99);

        $sideQuestEvent = $domainAction->execute($moment, $damageReceived, $this->combatHero, $this->heroCombatAttack, $this->combatMinion, true);

        $this->assertEquals($moment, $sideQuestEvent->moment);
        $this->assertEquals(SideQuestEvent::TYPE_MINION_BLOCKS_HERO, $sideQuestEvent->event_type);
    }

    /**
     * @test
     * @param $minionHealthReturned
     * @dataProvider provides_damage_dealt_tests
     */
    public function it_will_add_to_the_damages_dealt_for_the_hero_combat_attack($minionHealthReturned)
    {
        /** @var ProcessSideQuestHeroAttack $domainAction */
        $domainAction = app(ProcessSideQuestHeroAttack::class);
        $damageReceived = rand(10, 200);
        $moment = rand(1, 99);

        $this->assertEquals(0, count($this->heroCombatAttack->getDamagesDealt()));

        $combatMinion = \Mockery::mock($this->combatMinion)->shouldReceive('getCurrentHealth')->andReturn($minionHealthReturned)->getMock();
        $domainAction->execute($moment, $damageReceived, $this->combatHero, $this->heroCombatAttack, $combatMinion, false);

        $this->assertEquals(1, count($this->heroCombatAttack->getDamagesDealt()));
        $this->assertEquals($damageReceived, $this->heroCombatAttack->getDamagesDealt()[0]);
    }

    /**
     * @test
     * @param $minionHealthReturned
     * @dataProvider provides_damage_dealt_tests
     */
    public function it_will_add_to_the_damages_dealt_for_the_combat_hero($minionHealthReturned)
    {
        /** @var ProcessSideQuestHeroAttack $domainAction */
        $domainAction = app(ProcessSideQuestHeroAttack::class);
        $damageReceived = rand(10, 200);
        $moment = rand(1, 99);

        $this->assertEquals(0, count($this->combatHero->getDamagesDealt()));

        $combatMinion = \Mockery::mock($this->combatMinion)->shouldReceive('getCurrentHealth')->andReturn($minionHealthReturned)->getMock();
        $domainAction->execute($moment, $damageReceived, $this->combatHero, $this->heroCombatAttack, $combatMinion, false);

        $this->assertEquals(1, count($this->combatHero->getDamagesDealt()));
        $this->assertEquals($damageReceived, $this->combatHero->getDamagesDealt()[0]);
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
    public function it_will_return_a_side_quest_event_with_the_correct_resource_costs($minionHealthReturned, $block)
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
        $sideQuestEvent = $domainAction->execute($moment, $damageReceived, $this->combatHero, $heroCombatAttack, $combatMinion, $block);
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
    public function it_will_add_to_the_minion_kills_of_the_combat_hero()
    {
        /** @var ProcessSideQuestHeroAttack $domainAction */
        $domainAction = app(ProcessSideQuestHeroAttack::class);
        $damageReceived = rand(10, 200);
        $moment = rand(1, 99);

        $this->assertEquals(0, $this->combatHero->getMinionKills());

        $combatMinion = \Mockery::mock($this->combatMinion)->shouldReceive('getCurrentHealth')->andReturn(0)->getMock();
        $domainAction->execute($moment, $damageReceived, $this->combatHero, $this->heroCombatAttack, $combatMinion, false);

        $this->assertEquals(1, $this->combatHero->getMinionKills());
    }

    /**
     * @test
     */
    public function it_will_add_to_the_minion_kills_of_the_hero_combat_attack()
    {
        /** @var ProcessSideQuestHeroAttack $domainAction */
        $domainAction = app(ProcessSideQuestHeroAttack::class);
        $damageReceived = rand(10, 200);
        $moment = rand(1, 99);

        $this->assertEquals(0, $this->heroCombatAttack->getMinionKills());

        $combatMinion = \Mockery::mock($this->combatMinion)->shouldReceive('getCurrentHealth')->andReturn(0)->getMock();
        $domainAction->execute($moment, $damageReceived, $this->combatHero, $this->heroCombatAttack, $combatMinion, false);

        $this->assertEquals(1, $this->heroCombatAttack->getMinionKills());
    }
}
