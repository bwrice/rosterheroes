<?php

namespace Tests\Feature;

use App\Domain\Actions\CreateWeeklyGamePlayer;
use App\Domain\Collections\PositionCollection;
use App\Domain\Models\Game;
use App\Domain\Models\League;
use App\Domain\Models\Player;
use App\Domain\Models\PlayerGameLog;
use App\Domain\Models\PlayerStat;
use App\Domain\Models\Position;
use App\Domain\Models\StatType;
use App\Domain\Models\Team;
use App\Domain\Models\Week;
use App\Domain\Models\WeeklyGamePlayer;
use App\Exceptions\InvalidGameException;
use App\Exceptions\InvalidPlayerException;
use Carbon\Exceptions\InvalidDateException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateWeeklyGamePlayerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_game_starts_before_the_week_locks()
    {
        /** @var Week $week */
        $week = factory(Week::class)->create();
        /** @var Game $game */
        $game = factory(Game::class)->create([
            'starts_at' => $week->everything_locks_at->subMinutes(15)
        ]);
        $player = factory(Player::class)->create([
            'team_id' => $game->homeTeam->id
        ]);
        $action = new CreateWeeklyGamePlayer($week, $game, $player);
        try {

            $action(); //invoke

        } catch (InvalidGameException $exception) {

            $weeklyGamePlayer = WeeklyGamePlayer::query()
                ->where('game_id', '=', $game->id)
                ->where('player_id', '=', $player->id)
                ->where('week_id', '=', $week->id)->first();

            $this->assertNull($weeklyGamePlayer);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_player_isnt_a_part_of_the_game()
    {
        /** @var Week $week */
        $week = factory(Week::class)->create();
        /** @var Game $game */
        $game = factory(Game::class)->create([
            'starts_at' => $week->everything_locks_at->addMinutes(30)
        ]);
        $player = factory(Player::class)->create(); //random team should be created in factory

        $action = new CreateWeeklyGamePlayer($week, $game, $player);
        try {

            $action(); //invoke

        } catch (InvalidPlayerException $exception) {

            $weeklyGamePlayer = WeeklyGamePlayer::query()
                ->where('game_id', '=', $game->id)
                ->where('player_id', '=', $player->id)
                ->where('week_id', '=', $week->id)->first();

            $this->assertNull($weeklyGamePlayer);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_player_doesnt_have_any_positions_or_game_logs()
    {
        /** @var Week $week */
        $week = factory(Week::class)->create();
        /** @var Game $game */
        $game = factory(Game::class)->create([
            'starts_at' => $week->everything_locks_at->addMinutes(30)
        ]);
        /** @var Player $player */
        $player = factory(Player::class)->create([
            'team_id' => $game->awayTeam->id
        ]);

        $this->assertTrue($player->positions->isEmpty());
        $this->assertTrue($player->playerGameLogs->isEmpty());

        $action = new CreateWeeklyGamePlayer($week, $game, $player);
        try {

            $action(); //invoke

        } catch (InvalidPlayerException $exception) {

            $weeklyGamePlayer = WeeklyGamePlayer::query()
                ->where('game_id', '=', $game->id)
                ->where('player_id', '=', $player->id)
                ->where('week_id', '=', $week->id)->first();

            $this->assertNull($weeklyGamePlayer);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_set_the_salary_the_highest_default_of_the_players_positions()
    {
        $homeTeam = factory(Team::class)->create([
            'league_id' => League::nfl()->id
        ]);

        $awayTeam = factory(Team::class)->create([
            'league_id' => League::nfl()->id
        ]);

        /** @var Week $week */
        $week = factory(Week::class)->create();
        /** @var Game $game */
        $game = factory(Game::class)->create([
            'starts_at' => $week->everything_locks_at->addMinutes(30),
            'home_team_id' => $homeTeam->id,
            'away_team_id' => $awayTeam->id
        ]);
        /** @var Player $player */
        $player = factory(Player::class)->create([
            'team_id' => $homeTeam->id
        ]);

        /** @var PositionCollection $positions */
        $positions = Position::query()->whereIn('name', [
            Position::QUARTERBACK,
            Position::TIGHT_END
        ])->get();

        $highestDefaultSalary = $positions->withHighestPositionValue()->getBehavior()->getDefaultSalary();

        $player->positions()->saveMany($positions);

        $this->assertEquals(2, $player->positions->count());
        $this->assertTrue($player->playerGameLogs->isEmpty());

        $action = new CreateWeeklyGamePlayer($week, $game, $player);

        $weeklyGamePlayer = $action(); //invoke

        $this->assertEquals($game->id, $weeklyGamePlayer->game->id);
        $this->assertEquals($player->id, $weeklyGamePlayer->player->id);
        $this->assertEquals($week->id, $weeklyGamePlayer->week->id);
        $this->assertEquals($highestDefaultSalary, $weeklyGamePlayer->salary);
    }

    /**
     * @test
     */
    public function it_will_raise_the_salary_if_player_game_logs_have_high_total_points()
    {

        $league = League::mlb();

        $homeTeam = factory(Team::class)->create([
            'league_id' => $league->id
        ]);

        $awayTeam = factory(Team::class)->create([
            'league_id' => $league->id
        ]);

        /** @var Week $week */
        $week = factory(Week::class)->create();
        /** @var Game $game */
        $game = factory(Game::class)->create([
            'starts_at' => $week->everything_locks_at->addMinutes(30),
            'home_team_id' => $homeTeam->id,
            'away_team_id' => $awayTeam->id
        ]);
        /** @var Player $player */
        $player = factory(Player::class)->create([
            'team_id' => $awayTeam->id
        ]);

        $shortStop = Position::forName(Position::SHORTSTOP);

        $player->positions()->save($shortStop);

        $homeRuns = factory(PlayerStat::class)->make([
            'amount' => 4,
            'stat_type_id' => StatType::forName(StatType::HOME_RUN)->id
        ]);

        $runsBattedIn = factory(PlayerStat::class)->make([
            'amount' => 8,
            'stat_type_id' => StatType::forName(StatType::RUN_BATTED_IN)
        ]);

        /** @var PlayerGameLog $playerGameLog */
        $playerGameLog = factory(PlayerGameLog::class)->create([
            'player_id' => $player->id
        ]);

        $playerGameLog->playerStats()->saveMany([$homeRuns, $runsBattedIn]);

        $this->assertEquals(2, $playerGameLog->playerStats->count());
        $this->assertEquals(1, $player->positions->count());
        $this->assertEquals(1, $player->playerGameLogs->count());

        $action = new CreateWeeklyGamePlayer($week, $game, $player);

        $weeklyGamePlayer = $action(); //invoke

        $this->assertGreaterThan($shortStop->getBehavior()->getDefaultSalary(), $weeklyGamePlayer->salary);
    }

    /**
     * @test
     */
    public function it_will_lower_the_salary_if_player_game_logs_have_low_total_points()
    {

    }

    /**
     * @test
     */
    public function it_will_raise_the_salary_even_more_with_more_high_total_game_logs()
    {

    }

    /**
     * @test
     */
    public function it_will_lower_the_salary_even_more_with_more_low_total_game_logs()
    {

    }

    /**
     * @test
     */
    public function more_recent_game_logs_are_weighted_heavier_towards_the_salary_calculation()
    {

    }
}
