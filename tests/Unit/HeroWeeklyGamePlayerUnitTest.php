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
use App\Domain\Models\WeeklyGamePlayer;
use App\Domain\Models\Position;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\HeroPostType;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class HeroWeeklyGamePlayerUnitTest extends TestCase
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

        /** @var \App\Domain\Models\WeeklyGamePlayer $weeklyGamePlayer */
        $weeklyGamePlayer = factory(WeeklyGamePlayer::class)->create();
        Week::setTestCurrent($weeklyGamePlayer->week);

        $validPositions = $hero->heroRace->positions;

        // where NOT IN
        $playerPosition = Position::query()
            ->whereNotIn('id', $validPositions->pluck('id')->toArray())
            ->inRandomOrder()->first();

        $weeklyGamePlayer->gamePlayer->player->positions()->attach($playerPosition);

        try {

            $hero->addWeeklyGamePlayer($weeklyGamePlayer);

        } catch (InvalidPositionsException $e) {

            $hero = $hero->fresh();
            $this->assertNull($hero->weeklyGamePlayer);

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
            'squad_id' => $squad->id,
        ]);

        $playerWeekSalary = $squadSalary + 3000;
        /** @var \App\Domain\Models\WeeklyGamePlayer $weeklyGamePlayer */
        $weeklyGamePlayer = factory(WeeklyGamePlayer::class)->create([
            'salary' => $playerWeekSalary
        ]);

        Week::setTestCurrent($weeklyGamePlayer->week);

        /*
         * We don't get positions from the hero post type, but instead, get them from the
         * HeroRace directly. It's not this test's responsibility to check the hero's race is valid for the hero post
         */

        $validPositions = $hero->heroRace->positions;
        $validPositionIDs = $validPositions->pluck('id')->toArray();
        // where IN
        $playerPosition = Position::query()
            ->whereIn('id', $validPositionIDs)
            ->inRandomOrder()->first();

        $this->assertTrue(in_array($playerPosition->id, $validPositionIDs), 'Position ID');
        $weeklyGamePlayer->gamePlayer->player->positions()->attach($playerPosition);

        $this->assertEquals($squadSalary, $hero->availableSalary());

        try {

            $hero->addWeeklyGamePlayer($weeklyGamePlayer);

        } catch (NotEnoughSalaryException $e) {

            $hero = $hero->fresh();
            $this->assertNull($hero->weeklyGamePlayer);

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

        /** @var \App\Domain\Models\WeeklyGamePlayer $weeklyGamePlayer */
        $weeklyGamePlayer = factory(WeeklyGamePlayer::class)->create();

        Week::setTestCurrent($weeklyGamePlayer->week);
        CarbonImmutable::setTestNow($weeklyGamePlayer->gamePlayer->game->starts_at->copy()->addMinutes(5));

        $validPositions = $hero->heroRace->positions;

        // where IN
        $playerPosition = Position::query()
            ->whereIn('id', $validPositions->pluck('id')->toArray())
            ->inRandomOrder()->first();

        $weeklyGamePlayer->gamePlayer->player->positions()->attach($playerPosition);

        try {

            $hero->addWeeklyGamePlayer($weeklyGamePlayer);

        } catch (GameStartedException $e) {

            $hero = $hero->fresh();
            $this->assertNull($hero->weeklyGamePlayer);

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

        /** @var \App\Domain\Models\WeeklyGamePlayer $weeklyGamePlayer */
        $weeklyGamePlayer = factory(WeeklyGamePlayer::class)->create();

        /** @var \App\Domain\Models\Week $differentWeek */
        $differentWeek = factory(Week::class)->create();

        Week::setTestCurrent($differentWeek);
        CarbonImmutable::setTestNow($differentWeek->ends_at->copy()->subDays(2));

        $validPositions = $hero->heroRace->positions;

        // where IN
        $playerPosition = Position::query()
            ->whereIn('id', $validPositions->pluck('id')->toArray())
            ->inRandomOrder()->first();

        $weeklyGamePlayer->gamePlayer->player->positions()->attach($playerPosition);

        try {

            $hero->addWeeklyGamePlayer($weeklyGamePlayer);

        } catch (InvalidWeekException $e) {

            $hero = $hero->fresh();
            $this->assertNull($hero->weeklyGamePlayer);
            $this->assertEquals($e->getInvalidWeek(), $weeklyGamePlayer->week);

            return;
        }

        $this->fail("Exception not thrown");
    }
}
