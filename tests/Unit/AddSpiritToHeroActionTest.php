<?php

namespace Tests\Unit;

use App\Domain\Actions\AddSpiritToHeroAction;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroPost;
use App\Domain\Models\HeroRace;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Position;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Exceptions\HeroPlayerSpiritException;
use App\Facades\CurrentWeek;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Date;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AddSpiritToHeroActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Hero */
    protected $hero;
    /** @var PlayerSpirit */
    protected $playerSpirit;

    public function setUp(): void
    {
        parent::setUp();

        /** @var HeroRace $heroRace */
        $heroRace = HeroRace::query()->inRandomOrder()->first();

        /*
         * Create Hero of specific Hero Race
         */
        $this->hero = factory(Hero::class)->create([
            'hero_race_id' => $heroRace->id
        ]);

        /*
         * Created Player Spirit
         */
        $this->playerSpirit = factory(PlayerSpirit::class)->create();

        /*
         * Attach matching position
         */
        $position = $heroRace->positions()->inRandomOrder()->first();
        $this->playerSpirit->playerGameLog->player->positions()->attach($position);

        /*
         * Set current week to Player Spirit's week
         */
        CurrentWeek::setTestCurrent($this->playerSpirit->week);

        /*
         * Set current time to Before the week ends
         */
        Date::setTestNow(Week::current()->adventuring_locks_at->subHours(6));

        /*
         * Set game start time to AFTER the week ends
         */
        $game = $this->playerSpirit->playerGameLog->game;
        $game->starts_at = Week::current()->adventuring_locks_at->addHours(2);
        $game->save();

    }

    /**
     * @test
     */
    public function adding_a_spirit_for_a_non_current_week_will_throw_an_exception()
    {
        // set current week to different week
        factory(Week::class)->states('as-current')->create();

        try {

            /** @var AddSpiritToHeroAction $action */
            $action = app(AddSpiritToHeroAction::class);
            $action->execute($this->hero, $this->playerSpirit);

        } catch (HeroPlayerSpiritException $exception) {

            $this->assertEquals(HeroPlayerSpiritException::INVALID_WEEK, $exception->getCode());

            $hero = $this->hero->fresh();
            $this->assertNull($hero->playerSpirit);

            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function adding_a_spirit_with_invalid_positions_will_throw_an_exception()
    {

        $invalidPosition = Position::query()->whereDoesntHave('heroRaces', function(Builder $query) {
            $query->where('id', '=', $this->hero->heroRace->id);
        })->first();

        $this->playerSpirit->playerGameLog->player->positions()->sync([$invalidPosition->id]);

        try {

            /** @var AddSpiritToHeroAction $action */
            $action = app(AddSpiritToHeroAction::class);
            $action->execute($this->hero, $this->playerSpirit);

        } catch (HeroPlayerSpiritException $exception) {

            $this->assertEquals(HeroPlayerSpiritException::INVALID_PLAYER_POSITIONS, $exception->getCode());

            $hero = $this->hero->fresh();
            $this->assertNull($hero->playerSpirit);

            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function add_a_spirit_with_too_high_of_essence_cost_will_throw_an_exception()
    {

        $squadEssence = 9000;
        $squad = $this->hero->squad;
        $squad->spirit_essence = $squadEssence;
        $squad->save();

        $this->playerSpirit->essence_cost = $squadEssence + 1;
        $this->playerSpirit->save();

        try {

            /** @var AddSpiritToHeroAction $action */
            $action = app(AddSpiritToHeroAction::class);
            $action->execute($this->hero, $this->playerSpirit);

        } catch (HeroPlayerSpiritException $exception) {

            $hero = $this->hero->fresh();
            $this->assertNull($hero->playerSpirit);

            $this->assertEquals(HeroPlayerSpiritException::NOT_ENOUGH_ESSENCE, $exception->getCode());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /** @test */
    public function adding_a_spirit_for_a_game_thats_started_will_throw_an_exception()
    {

        $now = Date::now();
        $game = $this->playerSpirit->playerGameLog->game;
        $game->starts_at = $now->subHour();
        $game->save();

        try {

            /** @var AddSpiritToHeroAction $action */
            $action = app(AddSpiritToHeroAction::class);
            $action->execute($this->hero, $this->playerSpirit);

        } catch (HeroPlayerSpiritException $exception) {

            $hero = $this->hero->fresh();
            $this->assertNull($hero->playerSpirit);

            $this->assertEquals(HeroPlayerSpiritException::GAME_STARTED, $exception->getCode());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function replacing_a_spirit_for_a_game_thats_started_will_throw_an_exception()
    {
        $week = Week::current();

        /** @var PlayerSpirit $spiritToBeReplaced */
        $spiritToBeReplaced = factory(PlayerSpirit::class)->create([
            'week_id' => $week->id // Make sure we're using the same week
        ]);

        // Set game to before now
        $game = $spiritToBeReplaced->playerGameLog->game;
        $game->starts_at = Date::now()->subHour();
        $game->save();

        $this->hero->player_spirit_id = $spiritToBeReplaced->id;
        $this->hero->save();

        $this->assertTrue($spiritToBeReplaced->playerGameLog->game->hasStarted());

        try {

            /** @var AddSpiritToHeroAction $action */
            $action = app(AddSpiritToHeroAction::class);
            $action->execute($this->hero, $this->playerSpirit);

        } catch (HeroPlayerSpiritException $exception) {

            $hero = $this->hero->fresh();
            $this->assertEquals($hero->playerSpirit->id, $spiritToBeReplaced->id);

            $this->assertEquals(HeroPlayerSpiritException::GAME_STARTED, $exception->getCode());
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function adding_a_spirit_attached_to_another_squad_hero_will_throw_an_exception()
    {
        // Attach the player spirit to our other squad hero
        /** @var Hero $otherSquadHero */
        $otherSquadHero = factory(Hero::class)->create([
            'player_spirit_id' => $this->playerSpirit->id,
            'squad_id' => $this->hero->squad->id
        ]);

        try {

            /** @var AddSpiritToHeroAction $action */
            $action = app(AddSpiritToHeroAction::class);
            $action->execute($this->hero, $this->playerSpirit);

        } catch (HeroPlayerSpiritException $exception) {

            $hero = $this->hero->fresh();
            $this->assertNull($hero->playerSpirit);
            $this->assertEquals($this->playerSpirit->id, $otherSquadHero->player_spirit_id);

            $this->assertEquals(HeroPlayerSpiritException::SPIRIT_ALREADY_USED, $exception->getCode());
            return;
        }

        $this->fail("Exception not thrown");
    }

}
