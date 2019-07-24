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
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Date;
use Laravel\Passport\Passport;
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
         * Create Hero
         */
        $this->hero = factory(Hero::class)->create([
            'hero_race_id' => $heroRace->id
        ]);

        /*
         * Attach Hero to Hero Post
         */
        factory(HeroPost::class)->create([
            'hero_id' => $this->hero
        ]);

        /*
         * Created Player Spirit
         */
        $this->playerSpirit = factory(PlayerSpirit::class)->create();

        /*
         * Attach matching position
         */
        $position = $heroRace->positions()->inRandomOrder()->first();
        $this->playerSpirit->player->positions()->attach($position);

        /*
         * Set current week to Player Spirit's week
         */
        Week::setTestCurrent($this->playerSpirit->week);

        /*
         * Set current time to Before the week ends
         */
        Date::setTestNow(Week::current()->everything_locks_at->subHours(6));

        /*
         * Set game start time to AFTER the week ends
         */
        $this->playerSpirit->game->starts_at = Week::current()->everything_locks_at->addHours(2);
        $this->playerSpirit->game->save();

    }

    /**
     * @test
     */
    public function adding_a_spirit_for_a_non_current_week_will_throw_an_exception()
    {
        // set current week to different week
        Week::setTestCurrent(factory(Week::class)->create());

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

        $this->playerSpirit->player->positions()->sync([$invalidPosition->id]);

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
        $squad = $this->hero->heroPost->squad;
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
        $game = $this->playerSpirit->game;
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
        $game = $spiritToBeReplaced->game;
        $game->starts_at = Date::now()->subHour();
        $game->save();

        $this->hero->player_spirit_id = $spiritToBeReplaced->id;
        $this->hero->save();

        $this->assertTrue($spiritToBeReplaced->game->hasStarted());

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
        // TODO
        $this->assertTrue(true);
    }


}
