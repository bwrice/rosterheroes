<?php

namespace Tests\Unit;

use App\Domain\Actions\BuildWeeklyPlayerSpiritsAction;
use App\Domain\Models\Game;
use App\Domain\Models\Player;
use App\Domain\Models\PlayerSpirit;
use App\Domain\Models\Team;
use App\Domain\Models\Week;
use App\Facades\CurrentWeek;
use App\Factories\Models\GameFactory;
use App\Factories\Models\PlayerFactory;
use App\Factories\Models\PlayerGameLogFactory;
use App\Factories\Models\PlayerSpiritFactory;
use App\Jobs\CreatePlayerSpiritJob;
use App\Services\ModelServices\WeekService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class BuildWeeklyPlayerSpiritsActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Week */
    protected $week;

    public function setUp(): void
    {
        parent::setUp();
        $this->week = factory(Week::class)->state('as-current')->create();
    }

    /**
    * @test
    */
    public function it_will_queue_jobs_for_games_starting_at_the_same_time_adventuring_locks_at()
    {
        /** @var Player $playerOne */
        $playerOne = factory(Player::class)->create();
        /** @var Player $playerTwo */
        $playerTwo = factory(Player::class)->create([
            'team_id' => factory(Team::class)->create([
                'league_id' => $playerOne->team->league->id
            ])
        ]);

        $game = factory(Game::class)->create([
            'home_team_id' => $playerOne->team->id,
            'away_team_id' => $playerTwo->team->id,
            'starts_at' => $this->week->adventuring_locks_at
        ]);

        Queue::fake();

        /** @var BuildWeeklyPlayerSpiritsAction $domainAction */
        $domainAction = app(BuildWeeklyPlayerSpiritsAction::class);
        $domainAction->execute($this->week);

        Queue::assertPushed(CreatePlayerSpiritJob::class, 2);

        Queue::assertPushed(CreatePlayerSpiritJob::class, function (CreatePlayerSpiritJob $job) use ($playerOne, $game) {
            return $job->player->id === $playerOne->id
                && $job->game->id === $game->id
                && $job->week->id === $this->week->id;
        });

        Queue::assertPushed(CreatePlayerSpiritJob::class, function (CreatePlayerSpiritJob $job) use ($playerTwo, $game) {
            return $job->player->id === $playerTwo->id
                && $job->game->id === $game->id
                && $job->week->id === $this->week->id;
        });
    }

    /**
    * @test
    */
    public function it_will_not_queue_jobs_for_games_before_adventuring_locks_at()
    {
        /** @var Player $playerOne */
        $playerOne = factory(Player::class)->create();
        /** @var Player $playerTwo */
        $playerTwo = factory(Player::class)->create([
            'team_id' => factory(Team::class)->create([
                'league_id' => $playerOne->team->league->id
            ])
        ]);

        $game = factory(Game::class)->create([
            'home_team_id' => $playerOne->team->id,
            'away_team_id' => $playerTwo->team->id,
            'starts_at' => $this->week->adventuring_locks_at->subMinutes(15)
        ]);

        Queue::fake();
        Queue::assertNothingPushed();

        /** @var BuildWeeklyPlayerSpiritsAction $domainAction */
        $domainAction = app(BuildWeeklyPlayerSpiritsAction::class);
        $domainAction->execute($this->week);

        Queue::assertNotPushed(CreatePlayerSpiritJob::class, function (CreatePlayerSpiritJob $job) use ($playerOne, $game) {
            return $job->player->id === $playerOne->id
                && $job->game->id === $game->id
                && $job->week->id === $this->week->id;
        });

        Queue::assertNotPushed(CreatePlayerSpiritJob::class, function (CreatePlayerSpiritJob $job) use ($playerTwo, $game) {
            return $job->player->id === $playerTwo->id
                && $job->game->id === $game->id
                && $job->week->id === $this->week->id;
        });
    }

    /**
    * @test
    */
    public function it_will_not_queue_jobs_for_games_more_than_12_hours_after_adventuring_locks()
    {

        /** @var Player $playerOne */
        $playerOne = factory(Player::class)->create();
        /** @var Player $playerTwo */
        $playerTwo = factory(Player::class)->create([
            'team_id' => factory(Team::class)->create([
                'league_id' => $playerOne->team->league->id
            ])
        ]);

        $game = factory(Game::class)->create([
            'home_team_id' => $playerOne->team->id,
            'away_team_id' => $playerTwo->team->id,
            'starts_at' => $this->week->adventuring_locks_at->addHours(13)
        ]);

        Queue::fake();
        Queue::assertNothingPushed();

        /** @var BuildWeeklyPlayerSpiritsAction $domainAction */
        $domainAction = app(BuildWeeklyPlayerSpiritsAction::class);
        $domainAction->execute($this->week);

        Queue::assertNotPushed(CreatePlayerSpiritJob::class, function (CreatePlayerSpiritJob $job) use ($playerOne, $game) {
            return $job->player->id === $playerOne->id
                && $job->game->id === $game->id
                && $job->week->id === $this->week->id;
        });

        Queue::assertNotPushed(CreatePlayerSpiritJob::class, function (CreatePlayerSpiritJob $job) use ($playerTwo, $game) {
            return $job->player->id === $playerTwo->id
                && $job->game->id === $game->id
                && $job->week->id === $this->week->id;
        });
    }

    /**
    * @test
    */
    public function it_will_not_dispatch_a_job_if_the_player_spirit_already_exists_for_the_player_and_game()
    {
        /** @var Game $game */
        $game = factory(Game::class)->create([
            'starts_at' => $this->week->adventuring_locks_at->addHours(2)
        ]);

        /** @var PlayerSpirit $alreadyExistingPlayerSpirit */
        $playerGameLogFactory = PlayerGameLogFactory::new()->forGame($game);
        $alreadyExistingPlayerSpirit = PlayerSpiritFactory::new()
            ->withPlayerGameLog($playerGameLogFactory)
            ->forWeek($this->week)->create();


        /** @var Player $diffPlayer */
        $diffPlayer = factory(Player::class)->create([
            'team_id' => $game->home_team_id
        ]);

        Queue::fake();

        /** @var BuildWeeklyPlayerSpiritsAction $domainAction */
        $domainAction = app(BuildWeeklyPlayerSpiritsAction::class);
        $domainAction->execute($this->week);

        // assert existing spirit job not pushed
        Queue::assertNotPushed(CreatePlayerSpiritJob::class, function (CreatePlayerSpiritJob $job) use ($alreadyExistingPlayerSpirit) {
            return $job->player->id === $alreadyExistingPlayerSpirit->playerGameLog->player_id
                && $job->game->id === $alreadyExistingPlayerSpirit->playerGameLog->game_id;
        });

        // assert missing spirit job still pushed
        Queue::assertPushed(CreatePlayerSpiritJob::class, function (CreatePlayerSpiritJob $job) use ($diffPlayer, $game) {
            return $job->player->id === $diffPlayer->id
                && $job->game->id === $game->id;
        });
    }

    /**
    * @test
    */
    public function it_will_push_multiple_jobs_for_the_same_player_if_they_have_multiple_valid_games()
    {
        /** @var Player $player */
        $player = factory(Player::class)->create();
        /** @var Game $gameOne */
        $gameOne = factory(Game::class)->create([
            'starts_at' => $this->week->adventuring_locks_at->addHours(1),
            'home_team_id' => $player->team_id
        ]);
        /** @var Game $gameOne */
        $gameTwo = factory(Game::class)->create([
            'starts_at' => $this->week->adventuring_locks_at->addHours(5),
            'home_team_id' => $player->team_id
        ]);

        Queue::fake();

        /** @var BuildWeeklyPlayerSpiritsAction $domainAction */
        $domainAction = app(BuildWeeklyPlayerSpiritsAction::class);
        $domainAction->execute($this->week);

        // first game of double header
        Queue::assertPushed(CreatePlayerSpiritJob::class, function (CreatePlayerSpiritJob $job) use ($player, $gameOne) {
            return $job->player->id === $player->id
                && $job->game->id === $gameOne->id;
        });

        // second game of double header
        Queue::assertPushed(CreatePlayerSpiritJob::class, function (CreatePlayerSpiritJob $job) use ($player, $gameTwo) {
            return $job->player->id === $player->id
                && $job->game->id === $gameTwo->id;
        });
    }

    /**
     * @test
     */
    public function it_will_not_push_a_job_if_the_player_has_a_non_roster_status()
    {
        $RetiredPlayer = PlayerFactory::new()->retired()->create();
        $activePlayer = PlayerFactory::new()->withTeamID($RetiredPlayer->team_id)->create();

        $game = GameFactory::new()->forEitherTeam($RetiredPlayer->team)->forWeek($this->week)->create();

        Queue::fake();

        /** @var BuildWeeklyPlayerSpiritsAction $domainAction */
        $domainAction = app(BuildWeeklyPlayerSpiritsAction::class);
        $domainAction->execute($this->week);

        // Retired player
        Queue::assertNotPushed(CreatePlayerSpiritJob::class, function (CreatePlayerSpiritJob $job) use ($RetiredPlayer, $game) {
            return $job->player->id === $RetiredPlayer->id
                && $job->game->id === $game->id;
        });

        // Active Player
        Queue::assertPushed(CreatePlayerSpiritJob::class, function (CreatePlayerSpiritJob $job) use ($activePlayer, $game) {
            return $job->player->id === $activePlayer->id
                && $job->game->id === $game->id;
        });
    }
}
