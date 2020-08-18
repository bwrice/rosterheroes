<?php

namespace Tests\Feature;

use App\Domain\Actions\CreateSpiritsForGame;
use App\Domain\Models\Game;
use App\Domain\Models\Player;
use App\Domain\Models\Week;
use App\Factories\Models\GameFactory;
use App\Factories\Models\PlayerFactory;
use App\Factories\Models\PlayerSpiritFactory;
use App\Jobs\CreatePlayerSpiritJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CreateSpiritsForGameTest extends TestCase
{
    use DatabaseTransactions;

    /** @var Week */
    protected $week;

    /** @var Game */
    protected $game;

    public function setUp(): void
    {
        parent::setUp();
        $this->week = factory(Week::class)->create();
        $this->game = GameFactory::new()->create();
    }
    /**
     * @return CreateSpiritsForGame
     */
    protected function getDomainAction()
    {
        return app(CreateSpiritsForGame::class);
    }

    /**
     * @test
     */
    public function it_will_dispatch_create_spirit_jobs_for_the_games_home_team_players_with_roster_status()
    {
        $playerFactory = PlayerFactory::new()
            ->withTeamID($this->game->home_team_id)
            ->withStatus(Player::STATUS_ROSTER);

        $player1 = $playerFactory->create();
        $player2 = $playerFactory->create();

        Queue::fake();

        $this->getDomainAction()->execute($this->game, $this->week);

        Queue::assertPushed(CreatePlayerSpiritJob::class, function (CreatePlayerSpiritJob $job) use ($player1) {
            return $job->player->id === $player1->id
                && $job->game->id === $this->game->id
                && $job->week->id === $this->week->id;
        });

        Queue::assertPushed(CreatePlayerSpiritJob::class, function (CreatePlayerSpiritJob $job) use ($player2) {
            return $job->player->id === $player2->id
                && $job->game->id === $this->game->id
                && $job->week->id === $this->week->id;
        });
    }

    /**
     * @test
     */
    public function it_will_dispatch_create_spirit_jobs_for_the_games_away_team_players_with_roster_status()
    {
        $playerFactory = PlayerFactory::new()
            ->withTeamID($this->game->away_team_id)
            ->withStatus(Player::STATUS_ROSTER);

        $player1 = $playerFactory->create();
        $player2 = $playerFactory->create();

        Queue::fake();

        $this->getDomainAction()->execute($this->game, $this->week);

        Queue::assertPushed(CreatePlayerSpiritJob::class, function (CreatePlayerSpiritJob $job) use ($player1) {
            return $job->player->id === $player1->id
                && $job->game->id === $this->game->id
                && $job->week->id === $this->week->id;
        });

        Queue::assertPushed(CreatePlayerSpiritJob::class, function (CreatePlayerSpiritJob $job) use ($player2) {
            return $job->player->id === $player2->id
                && $job->game->id === $this->game->id
                && $job->week->id === $this->week->id;
        });
    }

    /**
     * @test
     * @param string $status
     * @dataProvider provides_it_will_not_dispatch_create_spirit_jobs_for_player_with_invalid_statuses
     */
    public function it_will_not_dispatch_create_spirit_jobs_for_player_with_invalid_statuses($status)
    {
        $player = PlayerFactory::new()->withTeamID($this->game->home_team_id)->withStatus($status)->create();

        Queue::fake();

        $this->getDomainAction()->execute($this->game, $this->week);

        Queue::assertNotPushed(CreatePlayerSpiritJob::class, function (CreatePlayerSpiritJob $job) use ($player) {
            return $job->player->id === $player->id
                && $job->game->id === $this->game->id
                && $job->week->id === $this->week->id;
        });
    }

    public function provides_it_will_not_dispatch_create_spirit_jobs_for_player_with_invalid_statuses()
    {
        return [
            Player::STATUS_RETIRED => [
                'status' => Player::STATUS_RETIRED
            ],
            Player::STATUS_FREE_AGENT => [
                'status' => Player::STATUS_RETIRED
            ],
            Player::STATUS_MINORS => [
                'status' => Player::STATUS_RETIRED
            ],
        ];
    }

    /**
     * @test
     */
    public function it_will_not_dispatch_jobs_for_players_with_existing_spirits_for_the_game()
    {
        $spirit = PlayerSpiritFactory::new()->forWeek($this->week)->create();

        Queue::fake();

        $this->getDomainAction()->execute($spirit->playerGameLog->game, $this->week);

        Queue::assertNotPushed(CreatePlayerSpiritJob::class, function (CreatePlayerSpiritJob $job) use ($spirit) {
            return $job->player->id === $spirit->playerGameLog->player->id;
        });
    }
}
