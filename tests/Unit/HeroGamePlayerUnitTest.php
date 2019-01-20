<?php

namespace Tests\Unit;

use App\Exceptions\GameStartedException;
use App\Exceptions\InvalidWeekException;
use App\Exceptions\InvalidPositionsException;
use App\Exceptions\NotEnoughSalaryException;
use App\Game;
use App\Hero;
use App\HeroClass;
use App\Heroes\HeroPosts\HeroPost;
use App\GamePlayer;
use App\Positions\Position;
use App\Squad;
use App\Weeks\Week;
use Carbon\Carbon;
use Tests\TestCase;

class HeroGamePlayerUnitTest extends TestCase
{
    /**
     * @test
     */
    public function adding_a_game_player_without_a_valid_position_will_throw_an_exception()
    {
        /** @var Hero $hero */
        $hero = factory(Hero::class)->create();

        /** @var HeroPost $heroPost */
        $heroPost = factory(HeroPost::class)->create([
            'hero_id' => $hero->id
        ]);

        /** @var GamePlayer $gamePlayer */
        $gamePlayer = factory(GamePlayer::class)->create();
        Week::setTestCurrent($gamePlayer->game->week);

        // where NOT IN
        $playerPosition = Position::query()
            ->whereNotIn('id', $heroPost->heroRace->positions->pluck('id')->toArray())
            ->inRandomOrder()->first();

        $gamePlayer->player->positions()->attach($playerPosition);

        try {

            $hero->addGamePlayer($gamePlayer);

        } catch (InvalidPositionsException $e) {

            $hero = $hero->fresh();
            $this->assertNull($hero->gamePlayer);

            Week::setTestCurrent(); //clear testing week
            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function adding_a_game_player_with_too_high_a_salary_will_throw_an_exception()
    {
        /** @var Hero $hero */
        $hero = factory(Hero::class)->create();

        $squadSalary = 5000;
        $squad = factory(Squad::class)->create([
            'salary' => $squadSalary
        ]);

        /** @var HeroPost $heroPost */
        $heroPost = factory(HeroPost::class)->create([
            'hero_id' => $hero->id,
            'squad_id' => $squad->id
        ]);

        $playerWeekSalary = $squadSalary + 3000;
        /** @var GamePlayer $gamePlayer */
        $gamePlayer = factory(GamePlayer::class)->create([
            'initial_salary' => $playerWeekSalary,
            'salary' => $playerWeekSalary
        ]);

        Week::setTestCurrent($gamePlayer->game->week);

        // where IN
        $playerPosition = Position::query()
            ->whereIn('id', $heroPost->heroRace->positions->pluck('id')->toArray())
            ->inRandomOrder()->first();

        $gamePlayer->player->positions()->attach($playerPosition);

        $this->assertEquals($squadSalary, $hero->availableSalary());

        try {

            $hero->addGamePlayer($gamePlayer);

        } catch (NotEnoughSalaryException $e) {

            $hero = $hero->fresh();
            $this->assertNull($hero->gamePlayer);

            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function adding_a_game_player_with_a_game_that_has_passed_will_throw_an_exception()
    {
        /** @var Hero $hero */
        $hero = factory(Hero::class)->create();

        /** @var HeroPost $heroPost */
        $heroPost = factory(HeroPost::class)->create([
            'hero_id' => $hero->id,
        ]);

        /** @var GamePlayer $gamePlayer */
        $gamePlayer = factory(GamePlayer::class)->create();

        Week::setTestCurrent($gamePlayer->game->week);
        Carbon::setTestNow($gamePlayer->game->starts_at->copy()->addMinutes(5));

        // where IN
        $playerPosition = Position::query()
            ->whereIn('id', $heroPost->heroRace->positions->pluck('id')->toArray())
            ->inRandomOrder()->first();

        $gamePlayer->player->positions()->attach($playerPosition);

        try {

            $hero->addGamePlayer($gamePlayer);

        } catch (GameStartedException $e) {

            $hero = $hero->fresh();
            $this->assertNull($hero->gamePlayer);

            return;
        }

        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function adding_a_player_game_for_a_different_week_will_throw_an_exception()
    {
        /** @var Hero $hero */
        $hero = factory(Hero::class)->create();

        /** @var HeroPost $heroPost */
        $heroPost = factory(HeroPost::class)->create([
            'hero_id' => $hero->id,
        ]);

        /** @var GamePlayer $gamePlayer */
        $gamePlayer = factory(GamePlayer::class)->create();

        /** @var Week $differentWeek */
        $differentWeek = factory(Week::class)->create();

        Week::setTestCurrent($differentWeek);
        Carbon::setTestNow($differentWeek->ends_at->copy()->subDays(2));

        // where IN
        $playerPosition = Position::query()
            ->whereIn('id', $heroPost->heroRace->positions->pluck('id')->toArray())
            ->inRandomOrder()->first();

        $gamePlayer->player->positions()->attach($playerPosition);

        try {

            $hero->addGamePlayer($gamePlayer);

        } catch (InvalidWeekException $e) {

            $hero = $hero->fresh();
            $this->assertNull($hero->gamePlayer);
            $this->assertEquals($e->getInvalidWeek(), $gamePlayer->game->week);

            return;
        }

        $this->fail("Exception not thrown");
    }
}
