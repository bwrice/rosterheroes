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
        $invalidPositionPlayerSpirit = factory(PlayerSpirit::class)->create([
            'player_id' => $invalidPlayer->id,
            'essence_cost' => $this->essencePerHero,
            'week_id' => $this->currentWeek->id
        ]);

        /** @var Player $validPositionPlayer */
        $validPositionPlayer = factory(Player::class)->create();
        $validPositionPlayer->positions()->attach($validPositions->random()->id);
        $validPositionPlayerSpirit = factory(PlayerSpirit::class)->create([
            'player_id' => $validPositionPlayer->id,
            'essence_cost' => $this->essencePerHero - 100, // Make essence cost less appealing than invalid position spirit
            'week_id' => $this->currentWeek->id
        ]);

        /** @var Player $invalidPlayer */
        $invalidPlayer = factory(Player::class)->create();
        $invalidPlayer->positions()->attach($invalidPositions->random()->id);
        $invalidPositionPlayerSpirit = factory(PlayerSpirit::class)->create([
            'player_id' => $invalidPlayer->id,
            'essence_cost' => $this->essencePerHero,
            'week_id' => $this->currentWeek->id
        ]);

        $spy = \Mockery::spy(AddSpiritToHeroAction::class);
        app()->instance(AddSpiritToHeroAction::class, $spy);

        /** @var AutoAttachSpiritToHeroAction $domainAction */
        $domainAction = app(AutoAttachSpiritToHeroAction::class);
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
        $tooHighEssencePlayerSpirit = factory(PlayerSpirit::class)->create([
            'player_id' => $validPositionPlayer->id,
            'essence_cost' => $this->essencePerHero + 2000, // Essence cost too high
            'week_id' => $this->currentWeek->id
        ]);

        /** @var Player $validPositionPlayer */
        $validPositionPlayer = factory(Player::class)->create();
        $validPositionPlayer->positions()->attach($validPositions->random()->id);
        $optimalEssencePlayerSpirit = factory(PlayerSpirit::class)->create([
            'player_id' => $validPositionPlayer->id,
            'essence_cost' => $this->essencePerHero,
            'week_id' => $this->currentWeek->id
        ]);

        $spy = \Mockery::spy(AddSpiritToHeroAction::class);
        app()->instance(AddSpiritToHeroAction::class, $spy);

        /** @var AutoAttachSpiritToHeroAction $domainAction */
        $domainAction = app(AutoAttachSpiritToHeroAction::class);
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
        $tooLowEssenceSpirit = factory(PlayerSpirit::class)->create([
            'player_id' => $validPositionPlayer->id,
            'essence_cost' => $this->essencePerHero,
            'week_id' => $this->currentWeek->id
        ]);

        /** @var Player $validPositionPlayer */
        $validPositionPlayer = factory(Player::class)->create();
        $validPositionPlayer->positions()->attach($validPositions->random()->id);
        $expectedPlayerSpirit = factory(PlayerSpirit::class)->create([
            'player_id' => $validPositionPlayer->id,
            'essence_cost' => $this->squadEssence - $otherSpiritCost,
            'week_id' => $this->currentWeek->id
        ]);

        /** @var Player $validPositionPlayer */
        $validPositionPlayer = factory(Player::class)->create();
        $validPositionPlayer->positions()->attach($validPositions->random()->id);
        $tooHighSpiritEssence = factory(PlayerSpirit::class)->create([
            'player_id' => $validPositionPlayer->id,
            'essence_cost' => ($this->squadEssence - $otherSpiritCost) + 100,
            'week_id' => $this->currentWeek->id
        ]);

        $spy = \Mockery::spy(AddSpiritToHeroAction::class);
        app()->instance(AddSpiritToHeroAction::class, $spy);

        /** @var AutoAttachSpiritToHeroAction $domainAction */
        $domainAction = app(AutoAttachSpiritToHeroAction::class);
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
        $invalidPositionPlayerSpirit = factory(PlayerSpirit::class)->create([
            'player_id' => $invalidPlayer->id,
            'essence_cost' => $this->essencePerHero,
            'week_id' => $this->currentWeek->id
        ]);

        $spy = \Mockery::spy(AddSpiritToHeroAction::class);
        app()->instance(AddSpiritToHeroAction::class, $spy);

        /** @var AutoAttachSpiritToHeroAction $domainAction */
        $domainAction = app(AutoAttachSpiritToHeroAction::class);
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
        $validPositionPlayerSpirit = factory(PlayerSpirit::class)->create([
            'player_id' => $validPositionPlayer->id,
            'essence_cost' => $this->essencePerHero, // Make essence cost less appealing than invalid position spirit
            'week_id' => $this->currentWeek->id
        ]);

        $spy = \Mockery::spy(AddSpiritToHeroAction::class);
        app()->instance(AddSpiritToHeroAction::class, $spy);

        /** @var AutoAttachSpiritToHeroAction $domainAction */
        $domainAction = app(AutoAttachSpiritToHeroAction::class);
        $domainAction->execute($this->hero);
        $spy->shouldNotHaveReceived('execute');
    }
}
