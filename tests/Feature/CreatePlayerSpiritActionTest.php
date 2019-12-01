<?php

namespace Tests\Feature;

use App\Console\Commands\BuildPlayerSpirits;
use App\Domain\Actions\CreatePlayerSpiritAction;
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
use App\Exceptions\CreatePlayerSpiritException;
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

class CreatePlayerSpiritActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Week */
    protected $week;
    /** @var Game */
    protected $game;
    /** @var Player */
    protected $player;

    public function setUp(): void
    {
        parent::setUp();
        $this->week = factory(Week::class)->create();
        $this->game = factory(Game::class)->create([
            'starts_at' => $this->week->adventuring_locks_at->addHour()
        ]);
        $this->player = factory(Player::class)->state('with-positions')->create([
            'team_id' => $this->game->homeTeam->id
        ]);
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_game_starts_before_the_week_locks()
    {
        $this->game->starts_at = $this->week->adventuring_locks_at->subMinutes(15);
        $this->game->save();
        $this->game = $this->game->fresh();

        try {
            /** @var CreatePlayerSpiritAction $domainAction */
            $domainAction = app(CreatePlayerSpiritAction::class);
            $domainAction->execute($this->week, $this->game, $this->player);

        } catch (CreatePlayerSpiritException $exception) {

            $this->assertEquals(CreatePlayerSpiritException::CODE_INVALID_GAME_TIME, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_players_team_is_not_part_of_the_game()
    {
        $this->player = factory(Player::class)->create(); //random team should be created in factory

        try {
            /** @var CreatePlayerSpiritAction $domainAction */
            $domainAction = app(CreatePlayerSpiritAction::class);
            $domainAction->execute($this->week, $this->game, $this->player);

        } catch (CreatePlayerSpiritException $exception) {

            $this->assertEquals(CreatePlayerSpiritException::CODE_TEAM_NOT_PART_OF_GAME, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function it_will_throw_an_exception_if_the_player_doesnt_have_any_positions()
    {
        $this->player->positions()->sync([]);
        $this->player = $this->player->fresh();

        try {
            /** @var CreatePlayerSpiritAction $domainAction */
            $domainAction = app(CreatePlayerSpiritAction::class);
            $domainAction->execute($this->week, $this->game, $this->player);

        } catch (CreatePlayerSpiritException $exception) {

            $this->assertEquals(CreatePlayerSpiritException::CODE_INVALID_PLAYER_POSITIONS, $exception->getCode());
            return;
        }
        $this->fail("Exception not thrown");
    }

    /**
     * @test
     */
    public function the_action_will_set_the_essence_cost_to_the_position_default_if_no_game_logs()
    {
        /** @var PositionCollection $positions */
        $positions = Position::query()->whereIn('name', [
            Position::QUARTERBACK,
            Position::TIGHT_END
        ])->get();

        $position = $positions->withHighestPositionValue();

        $this->player->positions()->sync([]); // clear positions created by setup first
        $this->player->positions()->saveMany($positions);

        $this->assertEquals(2, $this->player->positions->count());
        $this->assertTrue($this->player->playerGameLogs->isEmpty());

        /** @var CreatePlayerSpiritAction $domainAction */
        $domainAction = app(CreatePlayerSpiritAction::class);
        $playerSpirit = $domainAction->execute($this->week, $this->game, $this->player);

        $this->assertEquals($position->getDefaultEssenceCost(), $playerSpirit->essence_cost);
    }

    /**
     * @test
     */
    public function the_action_will_raise_the_essence_cost_if_player_game_logs_have_high_total_points()
    {
        $shortStop = Position::forName(Position::SHORTSTOP);

        $this->player->positions()->sync([]);
        $this->player->positions()->save($shortStop);

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
            'player_id' => $this->player->id
        ]);

        $this->player = $this->player->fresh();

        $playerGameLog->playerStats()->saveMany([$homeRuns, $runsBattedIn]);

        $this->assertEquals(2, $playerGameLog->playerStats->count());
        $this->assertEquals(1, $this->player->playerGameLogs->count());

        /** @var CreatePlayerSpiritAction $domainAction */
        $domainAction = app(CreatePlayerSpiritAction::class);
        $playerSpirit = $domainAction->execute($this->week, $this->game, $this->player);

        $this->assertGreaterThan($shortStop->getBehavior()->getDefaultEssenceCost(), $playerSpirit->essence_cost);
    }

    /**
     * @test
     */
    public function the_action_will_lower_the_essence_cost_if_player_game_logs_have_low_total_points()
    {
        $pointGuard = Position::forName(Position::POINT_GUARD);

        $this->player->positions()->sync([]);
        $this->player->positions()->save($pointGuard);

        $pointsMade = factory(PlayerStat::class)->make([
            'amount' => 5,
            'stat_type_id' => StatType::forName(StatType::POINT_MADE)->id
        ]);

        /** @var PlayerGameLog $playerGameLog */
        $playerGameLog = factory(PlayerGameLog::class)->create([
            'player_id' => $this->player->id
        ]);

        $playerGameLog->playerStats()->save($pointsMade);

        $this->assertEquals(1, $playerGameLog->playerStats->count());
        $this->assertEquals(1, $this->player->playerGameLogs->count());

        /** @var CreatePlayerSpiritAction $domainAction */
        $domainAction = app(CreatePlayerSpiritAction::class);
        $playerSpirit = $domainAction->execute($this->week, $this->game, $this->player);

        $this->assertLessThan($pointGuard->getBehavior()->getDefaultEssenceCost(), $playerSpirit->essence_cost);
    }

    /**
     * @test
     */
    public function the_action_will_raise_the_essence_cost_even_more_with_more_high_total_game_logs()
    {
        $leftWing = Position::forName(Position::LEFT_WING);

        $this->player->positions()->sync([]);
        $this->player->positions()->save($leftWing);

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
            'player_id' => $this->player->id
        ]);

        $playerGameLog->playerStats()->saveMany([$goals, $shotsOnGoal]);

        $this->assertEquals(2, $playerGameLog->playerStats->count());
        $this->assertEquals(1, $this->player->playerGameLogs->count());

        /** @var CreatePlayerSpiritAction $domainAction */
        $domainAction = app(CreatePlayerSpiritAction::class);
        $firstPlayerSpirit = $domainAction->execute($this->week, $this->game, $this->player->fresh());

        $this->assertGreaterThan($leftWing->getBehavior()->getDefaultEssenceCost(), $firstPlayerSpirit->essence_cost);

        // Add another player game log with loaded stats and create a new player spirit
        /** @var PlayerGameLog $playerGameLog */
        $newPlayerGameLog = factory(PlayerGameLog::class)->create([
            'player_id' => $this->player->id
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


        /** @var CreatePlayerSpiritAction $domainAction */
        $domainAction = app(CreatePlayerSpiritAction::class);
        $secondPlayerSpirit = $domainAction->execute($this->week, $this->game, $this->player->fresh());

        $this->assertGreaterThan($firstPlayerSpirit->essence_cost, $secondPlayerSpirit->essence_cost);
    }

    /**
     * @test
     */
    public function the_action_will_lower_the_essence_cost_even_more_with_more_low_total_game_logs()
    {

        $runningBack = Position::forName(Position::RUNNING_BACK);

        $this->player->positions()->sync([]);
        $this->player->positions()->save($runningBack);

        $rushingYards = factory(PlayerStat::class)->make([
            'amount' => 20,
            'stat_type_id' => StatType::forName(StatType::RUSH_YARD)->id
        ]);

        /** @var PlayerGameLog $playerGameLog */
        $playerGameLog = factory(PlayerGameLog::class)->create([
            'player_id' => $this->player->id
        ]);

        $playerGameLog->playerStats()->save($rushingYards);

        $this->assertEquals(1, $playerGameLog->playerStats->count());
        $this->assertEquals(1, $this->player->playerGameLogs->count());

        /** @var CreatePlayerSpiritAction $domainAction */
        $domainAction = app(CreatePlayerSpiritAction::class);
        $firstPlayerSpirit = $domainAction->execute($this->week, $this->game, $this->player->fresh());

        $this->assertLessThan($runningBack->getBehavior()->getDefaultEssenceCost(), $firstPlayerSpirit->essence_cost);

        // Add another player game log with terrible stats and create a new player spirit
        /** @var PlayerGameLog $playerGameLog */
        $newPlayerGameLog = factory(PlayerGameLog::class)->create([
            'player_id' => $this->player->id
        ]);

        $moreBadRushingYards = factory(PlayerStat::class)->make([
            'amount' => 20,
            'stat_type_id' => StatType::forName(StatType::RUSH_YARD)->id
        ]);

        $newPlayerGameLog->playerStats()->save($moreBadRushingYards);
        $this->assertEquals(1, $newPlayerGameLog->playerStats->count());


        /** @var CreatePlayerSpiritAction $domainAction */
        $domainAction = app(CreatePlayerSpiritAction::class);
        $secondPlayerSpirit = $domainAction->execute($this->week, $this->game, $this->player->fresh());
        $this->assertLessThan($firstPlayerSpirit->essence_cost, $secondPlayerSpirit->essence_cost);
    }

    /**
     * @test
     */
    public function it_will_weigh_more_recent_game_logs_heavier_towards_essence_cost_calculation()
    {
        $runningBack = Position::forName(Position::RUNNING_BACK);

        $this->player->positions()->sync([]);
        $this->player->positions()->save($runningBack);

        $goodRushingYards = factory(PlayerStat::class)->make([
            'amount' => 250,
            'stat_type_id' => StatType::forName(StatType::RUSH_YARD)->id
        ]);

        /** @var PlayerGameLog $goodAndOldGameLog */
        $goodAndOldGameLog = factory(PlayerGameLog::class)->create([
            'player_id' => $this->player->id,
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
            'player_id' => $this->player->id,
            'game_id' => factory(Game::class)->create([
                'starts_at' => Date::now()->subWeeks(1)
            ])
        ]);

        $badAndRecentGameLog->playerStats()->save($badRushingYards);
        $this->assertEquals(2, $this->player->playerGameLogs->count());

        /** @var CreatePlayerSpiritAction $domainAction */
        $domainAction = app(CreatePlayerSpiritAction::class);
        $badRecentGamePlayerSpirit = $domainAction->execute($this->week, $this->game, $this->player->fresh());

        // clean-up old stats
        $goodAndOldGameLog->playerStats()->delete();
        $goodAndOldGameLog->delete();
        $badAndRecentGameLog->playerStats()->delete();
        $badAndRecentGameLog->delete();

        /** @var PlayerGameLog $goodAndOldGameLog */
        $badAndOldGameLog = factory(PlayerGameLog::class)->create([
            'player_id' => $this->player->id,
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
            'player_id' => $this->player->id,
            'game_id' => factory(Game::class)->create([
                'starts_at' => Date::now()->subWeeks(1)
            ])
        ]);

        $goodRushingYards = factory(PlayerStat::class)->make([
            'amount' => 250,
            'stat_type_id' => StatType::forName(StatType::RUSH_YARD)->id
        ]);

        $goodAndRecentGameLog->playerStats()->save($goodRushingYards);

        $this->assertEquals(2, $this->player->playerGameLogs->count());

        /** @var CreatePlayerSpiritAction $domainAction */
        $domainAction = app(CreatePlayerSpiritAction::class);
        $goodRecentPlayerSpirit = $domainAction->execute($this->week, $this->game, $this->player->fresh());

        $this->assertGreaterThan($badRecentGamePlayerSpirit->essence_cost, $goodRecentPlayerSpirit->essence_cost);
    }
//
//    /**
//     * @test
//     */
//    public function the_build_command_will_dispatch_jobs()
//    {
//        // set test now to year in future to prevent overlap with real games
//        Date::setTestNow(now()->addYear());
//
//        /** @var Week $week */
//        $week = factory(Week::class)->create();
//
//        Week::setTestCurrent($week);
//
//        $gameOneHomeTeam = factory(Team::class)->create();
//        $gameOneAwayTeam = factory(Team::class)->create();
//        $gameTwoHomeTeam = factory(Team::class)->create();
//        $gameTwoAwayTeam = factory(Team::class)->create();
//
//        $gameOne = factory(Game::class)->create([
//            'home_team_id' => $gameOneHomeTeam->id,
//            'away_team_id' => $gameOneAwayTeam->id,
//            'starts_at' => $week->getGamesPeriod()->getStartDate()->addMinutes(15)
//        ]);
//
//        $gameTwo = factory(Game::class)->create([
//            'home_team_id' => $gameTwoHomeTeam->id,
//            'away_team_id' => $gameTwoAwayTeam->id,
//            'starts_at' => $week->getGamesPeriod()->getStartDate()->addMinutes(15)
//        ]);
//
//        $playerOne = factory(Player::class)->create([
//            'team_id' => $gameOneHomeTeam->id
//        ]);
//        $playerTwo = factory(Player::class)->create([
//            'team_id' => $gameOneAwayTeam->id
//        ]);
//        $playerThree = factory(Player::class)->create([
//            'team_id' => $gameTwoHomeTeam->id
//        ]);
//        $playerFour = factory(Player::class)->create([
//            'team_id' => $gameTwoAwayTeam->id
//        ]);
//
//        Queue::fake();
//
//        Queue::assertNothingPushed();
//
//        Artisan::call('week:build-player-spirits');
//
//
//        Queue::assertPushed(CreatePlayerSpiritJob::class, 4);
//    }
}
