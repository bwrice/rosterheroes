<?php

namespace Tests\Feature;

use App\Domain\Actions\AddSpiritToHeroAction;
use App\Domain\Actions\Testing\AutoAttachSpiritToHeroAction;
use App\Domain\Actions\Testing\AutoManageHeroAction;
use App\Domain\Models\Hero;
use App\Domain\Models\Player;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Position;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Factories\Models\PlayerGameLogFactory;
use App\Factories\Models\PlayerSpiritFactory;
use App\Helpers\EloquentMatcher;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AutoAttachSpiritToHeroActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Hero */
    protected $hero;

    /** @var Hero */
    protected $otherHeroForSquad;

    /** @var Squad */
    protected $squad;

    /** @var int */
    protected $squadEssence;

    /** @var int */
    protected $essencePerHero;

    /** @var Week */
    protected $currentWeek;

    public function setUp(): void
    {
        parent::setUp();

        $this->squadEssence = 10000;

        $this->squad = factory(Squad::class)->create([
            'spirit_essence' => $this->squadEssence
        ]);

        $this->hero = factory(Hero::class)->create([
            'squad_id' => $this->squad->id
        ]);

        $this->otherHeroForSquad = factory(Hero::class)->create([
            'squad_id' => $this->squad->id
        ]);

        $this->essencePerHero = 5000;

        $this->currentWeek = factory(Week::class)->states(['adventuring-open', 'as-current'])->create();

    }

    /**
    * @test
    */
    public function it_will_execute_add_spirit_to_hero_with_spirit_with_valid_position()
    {
        $validPositions = $this->hero->heroRace->positions;
        $invalidPositions = Position::query()->whereNotIn('id', $validPositions->pluck('id')->toArray())->get();

        /** @var Player $invalidPlayer */
        $invalidPlayer = factory(Player::class)->create();
        $invalidPlayer->positions()->attach($invalidPositions->random()->id);

        $playerGameLogFactory = PlayerGameLogFactory::new()->forPlayer($invalidPlayer);
        $invalidPositionPlayerSpirit = PlayerSpiritFactory::new()
            ->withPlayerGameLog($playerGameLogFactory)
            ->withEssenceCost($this->essencePerHero)
            ->forWeek($this->currentWeek)
            ->create();


        /** @var Player $validPositionPlayer */
        $validPositionPlayer = factory(Player::class)->create();
        $validPositionPlayer->positions()->attach($validPositions->random()->id);

        $playerGameLogFactory = PlayerGameLogFactory::new()->forPlayer($validPositionPlayer);
        $validPositionPlayerSpirit = PlayerSpiritFactory::new()
            ->withPlayerGameLog($playerGameLogFactory)
            ->withEssenceCost($this->essencePerHero - 100) // Make essence cost less appealing than invalid position spirit
            ->forWeek($this->currentWeek)
            ->create();


        /** @var Player $invalidPlayer */
        $invalidPlayer = factory(Player::class)->create();
        $invalidPlayer->positions()->attach($invalidPositions->random()->id);

        $playerGameLogFactory = PlayerGameLogFactory::new()->forPlayer($invalidPlayer);
        $invalidPositionPlayerSpirit = PlayerSpiritFactory::new()
            ->withPlayerGameLog($playerGameLogFactory)
            ->withEssenceCost($this->essencePerHero)
            ->forWeek($this->currentWeek)
            ->create();

        $spy = \Mockery::spy(AddSpiritToHeroAction::class);
        app()->instance(AddSpiritToHeroAction::class, $spy);

        $domainAction = $this->getDomainAction();
        $domainAction->execute($this->hero);

        $spy->shouldHaveReceived('execute')->with(EloquentMatcher::withExpected($this->hero), EloquentMatcher::withExpected($validPositionPlayerSpirit));
    }

    /**
    * @test
    */
    public function it_will_execute_add_spirit_with_essence_cost_under_essence_per_hero_when_multiple_heroes_missing_spirits()
    {
        $this->assertGreaterThan(1, $this->squad->heroes->filter(function (Hero $hero) {
            return is_null($hero->player_spirit_id);
        })->count());
        $validPositions = $this->hero->heroRace->positions;

        /** @var Player $validPositionPlayer */
        $validPositionPlayer = factory(Player::class)->create();
        $validPositionPlayer->positions()->attach($validPositions->random()->id);

        $playerGameLogFactory = PlayerGameLogFactory::new()->forPlayer($validPositionPlayer);
        $tooHighEssencePlayerSpirit = PlayerSpiritFactory::new()
            ->withPlayerGameLog($playerGameLogFactory)
            ->withEssenceCost($this->essencePerHero + 2000) // Essence cost too high
            ->forWeek($this->currentWeek)
            ->create();

        /** @var Player $validPositionPlayer */
        $validPositionPlayer = factory(Player::class)->create();
        $validPositionPlayer->positions()->attach($validPositions->random()->id);

        $playerGameLogFactory = PlayerGameLogFactory::new()->forPlayer($validPositionPlayer);
        $optimalEssencePlayerSpirit = PlayerSpiritFactory::new()
            ->withPlayerGameLog($playerGameLogFactory)
            ->withEssenceCost($this->essencePerHero)
            ->forWeek($this->currentWeek)
            ->create();

        $spy = \Mockery::spy(AddSpiritToHeroAction::class);
        app()->instance(AddSpiritToHeroAction::class, $spy);

        $domainAction = $this->getDomainAction();
        $domainAction->execute($this->hero);

        $spy->shouldHaveReceived('execute')->with(EloquentMatcher::withExpected($this->hero), EloquentMatcher::withExpected($optimalEssencePlayerSpirit));
    }

    /**
    * @test
    */
    public function it_will_use_up_essence_remaining_when_last_hero_of_squad_is_missing_a_player_spirit()
    {
        // attach spirit to other hero
        $this->otherHeroForSquad->player_spirit_id = factory(PlayerSpirit::class)->create([
            'essence_cost' => $otherSpiritCost = (int) ($this->essencePerHero - 2000),
            'week_id' => $this->currentWeek->id
        ])->id;
        $this->otherHeroForSquad->save();

        // assert last hero need spirit
        $this->assertEquals(1, $this->squad->heroes->filter(function (Hero $hero) {
            return is_null($hero->player_spirit_id);
        })->count());

        $validPositions = $this->hero->heroRace->positions;

        /** @var Player $validPositionPlayer */
        $validPositionPlayer = factory(Player::class)->create();
        $validPositionPlayer->positions()->attach($validPositions->random()->id);

        $playerGameLogFactory = PlayerGameLogFactory::new()->forPlayer($validPositionPlayer);
        $tooLowEssenceSpirit = PlayerSpiritFactory::new()
            ->withPlayerGameLog($playerGameLogFactory)
            ->withEssenceCost($this->essencePerHero)
            ->forWeek($this->currentWeek)
            ->create();

        /** @var Player $validPositionPlayer */
        $validPositionPlayer = factory(Player::class)->create();
        $validPositionPlayer->positions()->attach($validPositions->random()->id);

        $playerGameLogFactory = PlayerGameLogFactory::new()->forPlayer($validPositionPlayer);
        $expectedPlayerSpirit = PlayerSpiritFactory::new()
            ->withPlayerGameLog($playerGameLogFactory)
            ->withEssenceCost($this->squadEssence - $otherSpiritCost)
            ->forWeek($this->currentWeek)
            ->create();

        /** @var Player $validPositionPlayer */
        $validPositionPlayer = factory(Player::class)->create();
        $validPositionPlayer->positions()->attach($validPositions->random()->id);

        $playerGameLogFactory = PlayerGameLogFactory::new()->forPlayer($validPositionPlayer);
        $tooHighSpiritEssence = PlayerSpiritFactory::new()
            ->withPlayerGameLog($playerGameLogFactory)
            ->withEssenceCost(($this->squadEssence - $otherSpiritCost) + 100)
            ->forWeek($this->currentWeek)
            ->create();

        $spy = \Mockery::spy(AddSpiritToHeroAction::class);
        app()->instance(AddSpiritToHeroAction::class, $spy);

        $domainAction = $this->getDomainAction();
        $domainAction->execute($this->hero);

        $spy->shouldHaveReceived('execute')->with(EloquentMatcher::withExpected($this->hero), EloquentMatcher::withExpected($expectedPlayerSpirit));
    }

    /**
    * @test
    */
    public function it_will_not_execute_add_spirit_action_if_no_valid_player_spirits()
    {
        $validPositions = $this->hero->heroRace->positions;
        $invalidPositions = Position::query()->whereNotIn('id', $validPositions->pluck('id')->toArray())->get();

        /** @var Player $invalidPlayer */
        $invalidPlayer = factory(Player::class)->create();
        $invalidPlayer->positions()->attach($invalidPositions->random()->id);

        $playerGameLogFactory = PlayerGameLogFactory::new()->forPlayer($invalidPlayer);
        $validPositionPlayerSpirit = PlayerSpiritFactory::new()
            ->withPlayerGameLog($playerGameLogFactory)
            ->withEssenceCost($this->essencePerHero)
            ->forWeek($this->currentWeek)
            ->create();

        $spy = \Mockery::spy(AddSpiritToHeroAction::class);
        app()->instance(AddSpiritToHeroAction::class, $spy);

        $domainAction = $this->getDomainAction();
        $domainAction->execute($this->hero);

        $spy->shouldNotHaveReceived('execute');
    }

    /**
    * @test
    */
    public function it_will_not_execute_add_spirit_action_if_hero_already_has_player_spirit()
    {
        $this->hero->player_spirit_id = factory(PlayerSpirit::class)->create([
            'week_id' => $this->currentWeek->id
        ])->id;
        $this->hero->save();
        $this->hero = $this->hero->fresh();

        $validPositions = $this->hero->heroRace->positions;

        /** @var Player $validPositionPlayer */
        $validPositionPlayer = factory(Player::class)->create();
        $validPositionPlayer->positions()->attach($validPositions->random()->id);

        $playerGameLogFactory = PlayerGameLogFactory::new()->forPlayer($validPositionPlayer);
        $validPositionPlayerSpirit = PlayerSpiritFactory::new()
            ->withPlayerGameLog($playerGameLogFactory)
            ->withEssenceCost($this->essencePerHero)
            ->forWeek($this->currentWeek)
            ->create();

        $spy = \Mockery::spy(AddSpiritToHeroAction::class);
        app()->instance(AddSpiritToHeroAction::class, $spy);

        $domainAction = $this->getDomainAction();
        $domainAction->execute($this->hero);

        $spy->shouldNotHaveReceived('execute');
    }

    /**
     * @return AutoAttachSpiritToHeroAction
     */
    protected function getDomainAction()
    {
        $domainAction = app(AutoAttachSpiritToHeroAction::class);
        // set rand essence range to 0 so we can guarantee expectations
        return $domainAction->setRandEssenceRange(0);
    }
}
