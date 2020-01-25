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
    public function it_will_execute_add_spirit_with_the_higher_essence_cost_under_max_allowed()
    {
        $validPositions = $this->hero->heroRace->positions;
        /** @var Player $validPositionPlayer */
        $validPositionPlayer = factory(Player::class)->create();
        $validPositionPlayer->positions()->attach($validPositions->random()->id);
        $lowEssencePlayerSpirit = factory(PlayerSpirit::class)->create([
            'player_id' => $validPositionPlayer->id,
            'essence_cost' => $this->essencePerHero - 2000, // Make essence cost less appealing than invalid position spirit
            'week_id' => $this->currentWeek->id
        ]);

        /** @var Player $validPositionPlayer */
        $validPositionPlayer = factory(Player::class)->create();
        $validPositionPlayer->positions()->attach($validPositions->random()->id);
        $tooHighEssencePlayerSpirit = factory(PlayerSpirit::class)->create([
            'player_id' => $validPositionPlayer->id,
            'essence_cost' => $this->essencePerHero + 2000, // Make essence cost less appealing than invalid position spirit
            'week_id' => $this->currentWeek->id
        ]);

        /** @var Player $validPositionPlayer */
        $validPositionPlayer = factory(Player::class)->create();
        $validPositionPlayer->positions()->attach($validPositions->random()->id);
        $optimalEssencePlayerSpirit = factory(PlayerSpirit::class)->create([
            'player_id' => $validPositionPlayer->id,
            'essence_cost' => $this->essencePerHero, // Make essence cost less appealing than invalid position spirit
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
}
