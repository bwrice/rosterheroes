<?php

namespace Tests\Feature;

use App\Console\Commands\BuildPlayerSpirits;
use App\Domain\Actions\CreatePlayerSpirit;
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
use App\Domain\Models\PlayerSpirit;
use App\Exceptions\InvalidGameException;
use App\Exceptions\InvalidPlayerException;
use App\Jobs\CreatePlayerSpiritJob;
use Carbon\Exceptions\InvalidDateException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatePlayerSpiritTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function the_job_will_throw_an_exception_if_the_game_starts_before_the_week_locks()
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

        try {

            CreatePlayerSpiritJob::dispatchNow($week, $game, $player);

        } catch (InvalidGameException $exception) {

            $playerSpirit = PlayerSpirit::query()
                ->where('game_id', '=', $game->id)
                ->where('player_id', '=', $player->id)
                ->where('week_id', '=', $week->id)->first();

            $this->assertNull($playerSpirit);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function the_job_will_throw_an_exception_if_the_player_isnt_a_part_of_the_game()
    {
        /** @var Week $week */
        $week = factory(Week::class)->create();
        /** @var Game $game */
        $game = factory(Game::class)->create([
            'starts_at' => $week->everything_locks_at->addMinutes(30)
        ]);
        $player = factory(Player::class)->create(); //random team should be created in factory

        try {

            CreatePlayerSpiritJob::dispatchNow($week, $game, $player);

        } catch (InvalidPlayerException $exception) {

            $playerSpirit = PlayerSpirit::query()
                ->where('game_id', '=', $game->id)
                ->where('player_id', '=', $player->id)
                ->where('week_id', '=', $week->id)->first();

            $this->assertNull($playerSpirit);
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

        try {

            CreatePlayerSpiritJob::dispatchNow($week, $game, $player);

        } catch (InvalidPlayerException $exception) {

            $playerSpirit = PlayerSpirit::query()
                ->where('game_id', '=', $game->id)
                ->where('player_id', '=', $player->id)
                ->where('week_id', '=', $week->id)->first();

            $this->assertNull($playerSpirit);
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function the_action_will_set_the_salary_to_the_position_default_if_no_game_logs()
    {
        /** @var Week $week */
        $week = factory(Week::class)->create();
        /** @var Game $game */
        $game = factory(Game::class)->create();
        /** @var Player $player */
        $player = factory(Player::class)->create();

        /** @var PositionCollection $positions */
        $positions = Position::query()->whereIn('name', [
            Position::QUARTERBACK,
            Position::TIGHT_END
        ])->get();

        $position = $positions->withHighestPositionValue();

        $player->positions()->saveMany($positions);

        $this->assertEquals(2, $player->positions->count());
        $this->assertTrue($player->playerGameLogs->isEmpty());

        $action = new CreatePlayerSpirit($week, $game, $player, $position);

        $playerSpirit = $action(); //invoke

        $this->assertEquals($position->getDefaultSalary(), $playerSpirit->salary);
    }

    /**
     * @test
     */
    public function the_action_will_raise_the_salary_if_player_game_logs_have_high_total_points()
    {
        /** @var Week $week */
        $week = factory(Week::class)->create();
        /** @var Game $game */
        $game = factory(Game::class)->create();
        /** @var Player $player */
        $player = factory(Player::class)->create();

        $shortStop = Position::forName(Position::SHORTSTOP);

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
        $this->assertEquals(1, $player->playerGameLogs->count());

        $action = new CreatePlayerSpirit($week, $game, $player, $shortStop);

        $playerSpirit = $action(); //invoke

        $this->assertGreaterThan($shortStop->getBehavior()->getDefaultSalary(), $playerSpirit->salary);
    }

    /**
     * @test
     */
    public function the_action_will_lower_the_salary_if_player_game_logs_have_low_total_points()
    {
        /** @var Week $week */
        $week = factory(Week::class)->create();
        /** @var Game $game */
        $game = factory(Game::class)->create();
        /** @var Player $player */
        $player = factory(Player::class)->create();

        $pointGuard = Position::forName(Position::POINT_GUARD);

        $pointsMade = factory(PlayerStat::class)->make([
            'amount' => 5,
            'stat_type_id' => StatType::forName(StatType::POINT_MADE)->id
        ]);

        /** @var PlayerGameLog $playerGameLog */
        $playerGameLog = factory(PlayerGameLog::class)->create([
            'player_id' => $player->id
        ]);

        $playerGameLog->playerStats()->save($pointsMade);

        $this->assertEquals(1, $playerGameLog->playerStats->count());
        $this->assertEquals(1, $player->playerGameLogs->count());

        $action = new CreatePlayerSpirit($week, $game, $player, $pointGuard);

        $playerSpirit = $action(); //invoke

        $this->assertLessThan($pointGuard->getBehavior()->getDefaultSalary(), $playerSpirit->salary);
    }

    /**
     * @test
     */
    public function the_action_will_raise_the_salary_even_more_with_more_high_total_game_logs()
    {
        /** @var Week $week */
        $week = factory(Week::class)->create();
        /** @var Game $game */
        $game = factory(Game::class)->create();

        /** @var Player $player */
        $player = factory(Player::class)->create();

        $leftWing = Position::forName(Position::LEFT_WING);

        $goals = factory(PlayerStat::class)->make([
            'amount' => 3,
            'stat_type_id' => StatType::forName(StatType::GOAL)->id
        ]);

        $shotsOnGoal = factory(PlayerStat::class)->make([
            'amount' => 8,
            'stat_type_id' => StatType::forName(StatType::SHOT_ON_GOAL)
        ]);

        /** @var PlayerGameLog $playerGameLog */
        $playerGameLog = factory(PlayerGameLog::class)->create([
            'player_id' => $player->id
        ]);

        $playerGameLog->playerStats()->saveMany([$goals, $shotsOnGoal]);

        $this->assertEquals(2, $playerGameLog->playerStats->count());
        $this->assertEquals(1, $player->playerGameLogs->count());

        $action = new CreatePlayerSpirit($week, $game, $player, $leftWing);

        $firstPlayerSpirit = $action(); //invoke

        $this->assertGreaterThan($leftWing->getBehavior()->getDefaultSalary(), $firstPlayerSpirit->salary);

        // Add another player game log with loaded stats and create a new player spirit
        /** @var PlayerGameLog $playerGameLog */
        $newPlayerGameLog = factory(PlayerGameLog::class)->create([
            'player_id' => $player->id
        ]);

        $moreGoals = factory(PlayerStat::class)->make([
            'amount' => 3,
            'stat_type_id' => StatType::forName(StatType::GOAL)->id
        ]);

        $evenMoreShotsOnGoal = factory(PlayerStat::class)->make([
            'amount' => 8,
            'stat_type_id' => StatType::forName(StatType::SHOT_ON_GOAL)
        ]);


        $newPlayerGameLog->playerStats()->saveMany([$moreGoals, $evenMoreShotsOnGoal]);
        $this->assertEquals(2, $newPlayerGameLog->playerStats->count());

        $action = new CreatePlayerSpirit($week, $game, $player->fresh(), $leftWing);

        $secondPlayerSpirit = $action(); //invoke
        $this->assertGreaterThan($firstPlayerSpirit->salary, $secondPlayerSpirit->salary);
    }

    /**
     * @test
     */
    public function the_action_will_lower_the_salary_even_more_with_more_low_total_game_logs()
    {
        /** @var Week $week */
        $week = factory(Week::class)->create();
        /** @var Game $game */
        $game = factory(Game::class)->create();
        /** @var Player $player */
        $player = factory(Player::class)->create();

        $runningBack = Position::forName(Position::RUNNING_BACK);

        $rushingYards = factory(PlayerStat::class)->make([
            'amount' => 20,
            'stat_type_id' => StatType::forName(StatType::RUSH_YARD)->id
        ]);

        /** @var PlayerGameLog $playerGameLog */
        $playerGameLog = factory(PlayerGameLog::class)->create([
            'player_id' => $player->id
        ]);

        $playerGameLog->playerStats()->save($rushingYards);

        $this->assertEquals(1, $playerGameLog->playerStats->count());
        $this->assertEquals(1, $player->playerGameLogs->count());

        $action = new CreatePlayerSpirit($week, $game, $player, $runningBack);

        $firstPlayerSpirit = $action(); //invoke

        $this->assertLessThan($runningBack->getBehavior()->getDefaultSalary(), $firstPlayerSpirit->salary);

        // Add another player game log with terrible stats and create a new player spirit
        /** @var PlayerGameLog $playerGameLog */
        $newPlayerGameLog = factory(PlayerGameLog::class)->create([
            'player_id' => $player->id
        ]);

        $moreBadRushingYards = factory(PlayerStat::class)->make([
            'amount' => 20,
            'stat_type_id' => StatType::forName(StatType::RUSH_YARD)->id
        ]);

        $newPlayerGameLog->playerStats()->save($moreBadRushingYards);
        $this->assertEquals(1, $newPlayerGameLog->playerStats->count());

        $action = new CreatePlayerSpirit($week, $game, $player->fresh(), $runningBack);

        $secondPlayerSpirit = $action(); //invoke
        $this->assertLessThan($firstPlayerSpirit->salary, $secondPlayerSpirit->salary);
    }

    /**
     * @test
     */
    public function the_action_will_weigh_more_recent_game_logs_heavier_towards_salary_calculation()
    {
        /** @var Week $week */
        $week = factory(Week::class)->create();
        /** @var Game $game */
        $game = factory(Game::class)->create();
        /** @var Player $playerWithBadRecentGame */
        $playerWithBadRecentGame = factory(Player::class)->create();

        $runningBack = Position::forName(Position::RUNNING_BACK);

        $goodRushingYards = factory(PlayerStat::class)->make([
            'amount' => 250,
            'stat_type_id' => StatType::forName(StatType::RUSH_YARD)->id
        ]);

        /** @var PlayerGameLog $goodAndOldGameLog */
        $goodAndOldGameLog = factory(PlayerGameLog::class)->create([
            'player_id' => $playerWithBadRecentGame->id,
            'game_id' => factory(Game::class)->create([
                'starts_at' => Date::now()->subWeeks(5)
            ])
        ]);

        $goodAndOldGameLog->playerStats()->save($goodRushingYards);

        $badRushingYards = factory(PlayerStat::class)->make([
            'amount' => 20,
            'stat_type_id' => StatType::forName(StatType::RUSH_YARD)->id
        ]);

        /** @var PlayerGameLog $badAndRecentGameLog */
        $badAndRecentGameLog = factory(PlayerGameLog::class)->create([
            'player_id' => $playerWithBadRecentGame->id,
            'game_id' => factory(Game::class)->create([
                'starts_at' => Date::now()->subWeeks(1)
            ])
        ]);

        $badAndRecentGameLog->playerStats()->save($badRushingYards);
        $this->assertEquals(2, $playerWithBadRecentGame->playerGameLogs->count());

        $action = new CreatePlayerSpirit($week, $game, $playerWithBadRecentGame, $runningBack);

        $badRecentPlayerSpirit = $action(); //invoke

        /** @var Player $player */
        $playerWithGoodRecentGame = factory(Player::class)->create();

        /** @var PlayerGameLog $goodAndOldGameLog */
        $badAndOldGameLog = factory(PlayerGameLog::class)->create([
            'player_id' => $playerWithGoodRecentGame->id,
            'game_id' => factory(Game::class)->create([
                'starts_at' => Date::now()->subWeeks(5)
            ])
        ]);

        $badRushingYards = factory(PlayerStat::class)->make([
            'amount' => 20,
            'stat_type_id' => StatType::forName(StatType::RUSH_YARD)->id
        ]);

        $badAndOldGameLog->playerStats()->save($badRushingYards);

        /** @var PlayerGameLog $badAndRecentGameLog */
        $goodAndRecentGameLog = factory(PlayerGameLog::class)->create([
            'player_id' => $playerWithGoodRecentGame->id,
            'game_id' => factory(Game::class)->create([
                'starts_at' => Date::now()->subWeeks(1)
            ])
        ]);

        $goodRushingYards = factory(PlayerStat::class)->make([
            'amount' => 250,
            'stat_type_id' => StatType::forName(StatType::RUSH_YARD)->id
        ]);

        $goodAndRecentGameLog->playerStats()->save($goodRushingYards);

        $this->assertEquals(2, $playerWithGoodRecentGame->playerGameLogs->count());

        $action = new CreatePlayerSpirit($week, $game, $playerWithGoodRecentGame, $runningBack);

        $goodRecentPlayerSpirit = $action(); //invoke
        $this->assertGreaterThan($badRecentPlayerSpirit->salary, $goodRecentPlayerSpirit->salary);
    }

    /**
     * @test
     */
    public function the_build_command_will_dispatch_jobs()
    {
        // set test now to year in future to prevent overlap with real games
        Date::setTestNow(now()->addYear());

        /** @var Week $week */
        $week = factory(Week::class)->create();

        Week::setTestCurrent($week);

        $gameOneHomeTeam = factory(Team::class)->create();
        $gameOneAwayTeam = factory(Team::class)->create();
        $gameTwoHomeTeam = factory(Team::class)->create();
        $gameTwoAwayTeam = factory(Team::class)->create();

        $gameOne = factory(Game::class)->create([
            'home_team_id' => $gameOneHomeTeam->id,
            'away_team_id' => $gameOneAwayTeam->id,
            'starts_at' => $week->getGamesPeriod()->getStartDate()->addMinutes(15)
        ]);

        $gameTwo = factory(Game::class)->create([
            'home_team_id' => $gameTwoHomeTeam->id,
            'away_team_id' => $gameTwoAwayTeam->id,
            'starts_at' => $week->getGamesPeriod()->getStartDate()->addMinutes(15)
        ]);

        $playerOne = factory(Player::class)->create([
            'team_id' => $gameOneHomeTeam->id
        ]);
        $playerTwo = factory(Player::class)->create([
            'team_id' => $gameOneAwayTeam->id
        ]);
        $playerThree = factory(Player::class)->create([
            'team_id' => $gameTwoHomeTeam->id
        ]);
        $playerFour = factory(Player::class)->create([
            'team_id' => $gameTwoAwayTeam->id
        ]);

        Queue::fake();

        Queue::assertNothingPushed();

        Artisan::call('week:build-player-spirits');


        Queue::assertPushed(CreatePlayerSpiritJob::class, 4);
    }
}
