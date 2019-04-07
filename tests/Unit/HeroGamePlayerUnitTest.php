<?php

namespace Tests\Unit;

use App\Exceptions\GameStartedException;
use App\Exceptions\InvalidWeekException;
use App\Exceptions\InvalidPositionsException;
use App\Exceptions\NotEnoughSalaryException;
use App\Domain\Models\Game;
use App\Domain\Models\Hero;
use App\Domain\Models\HeroClass;
use App\Domain\Models\HeroPost;
use App\Domain\Models\GamePlayer;
use App\Domain\Models\Position;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class HeroGamePlayerUnitTest extends TestCase
{
    use DatabaseTransactions;

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

        /** @var \App\Domain\Models\GamePlayer $gamePlayer */
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
        /** @var \App\Domain\Models\Hero $hero */
        $hero = factory(Hero::class)->create();

        $squadSalary = 5000;
        $squad = factory(Squad::class)->create([
            'salary' => $squadSalary
        ]);

        /** @var \App\Domain\Models\HeroPost $heroPost */
        $heroPost = factory(HeroPost::class)->create([
            'hero_id' => $hero->id,
            'squad_id' => $squad->id
        ]);

        $playerWeekSalary = $squadSalary + 3000;
        /** @var \App\Domain\Models\GamePlayer $gamePlayer */
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

        /** @var \App\Domain\Models\HeroPost $heroPost */
        $heroPost = factory(HeroPost::class)->create([
            'hero_id' => $hero->id,
        ]);

        /** @var \App\Domain\Models\GamePlayer $gamePlayer */
        $gamePlayer = factory(GamePlayer::class)->create();

        Week::setTestCurrent($gamePlayer->game->week);
        CarbonImmutable::setTestNow($gamePlayer->game->starts_at->copy()->addMinutes(5));

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

        /** @var \App\Domain\Models\GamePlayer $gamePlayer */
        $gamePlayer = factory(GamePlayer::class)->create();

        /** @var \App\Domain\Models\Week $differentWeek */
        $differentWeek = factory(Week::class)->create();

        Week::setTestCurrent($differentWeek);
        CarbonImmutable::setTestNow($differentWeek->ends_at->copy()->subDays(2));

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
