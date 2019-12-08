<?php

namespace Tests\Feature;

use App\Domain\Actions\BuildPlayerGameLogsForGameAction;
use App\Domain\Collections\GameLogDTOCollection;
use App\Domain\DataTransferObjects\PlayerGameLogDTO;
use App\Domain\DataTransferObjects\StatAmountDTO;
use App\Domain\Models\Game;
use App\Domain\Models\Player;
use App\Domain\Models\PlayerGameLog;
use App\Domain\Models\Position;
use App\Domain\Models\StatType;
use App\External\Stats\MockIntegration;
use App\External\Stats\StatsIntegration;
use App\Jobs\BuildPlayerGameLogsJob;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BuildPlayerGameLogsForGameActionTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_will_create_new_player_game_logs()
    {
        /** @var Game $game */
        $game = factory(Game::class)->create();
        $homeTeam = $game->homeTeam;

        $player = factory(Player::class)->create([
            'team_id' => $homeTeam->id
        ]);

        $sport = $homeTeam->league->sport;

        $statTypes = $sport->statTypes()->inRandomOrder()->take(rand(1,5))->get();

        $statAmountDTOs = $statTypes->map(function (StatType $statType) {
            return new StatAmountDTO($statType,rand(1,50)/2);
        });

        $firstPlayerDTO = new PlayerGameLogDTO($player, $game, $homeTeam, $statAmountDTOs);

        $player = factory(Player::class)->create([
            'team_id' => $homeTeam->id
        ]);

        $statTypes = $sport->statTypes()->inRandomOrder()->take(rand(1,5))->get();

        $statAmountDTOs = $statTypes->map(function (StatType $statType) {
            return new StatAmountDTO($statType,rand(1,50)/2);
        });

        $secondPlayerDTO = new PlayerGameLogDTO($player, $game, $homeTeam, $statAmountDTOs);

        $player = factory(Player::class)->create([
            'team_id' => $homeTeam->id
        ]);

        $statTypes = $sport->statTypes()->inRandomOrder()->take(rand(1,5))->get();

        $statAmountDTOs = $statTypes->map(function (StatType $statType) {
            return new StatAmountDTO($statType,rand(1,50)/2);
        });

        $thirdPlayerDTO = new PlayerGameLogDTO($player, $game, $homeTeam, $statAmountDTOs);

        $playerGameDTOs = new GameLogDTOCollection([
            $firstPlayerDTO,
            $secondPlayerDTO,
            $thirdPlayerDTO
        ]);

        $mockIntegration = new MockIntegration(null,null,null, $playerGameDTOs);
        app()->instance(StatsIntegration::class, $mockIntegration);

        /** @var BuildPlayerGameLogsForGameAction $domainAction */
        $domainAction = app(BuildPlayerGameLogsForGameAction::class);
        $domainAction->execute($game);

        $playerGameLogs = PlayerGameLog::query()->with('playerStats')->where('game_id', '=', $game->id)->get();
        $this->assertEquals(3, $playerGameLogs->count());
        $playerGameLogs->each(function (PlayerGameLog $playerGameLog) use ($homeTeam) {
            $this->assertEquals($homeTeam->id, $playerGameLog->team->id);
            $this->assertGreaterThan(0, $playerGameLog->playerStats->count());
        });
    }

    /**
    * @test
    */
    public function it_will_finalize_a_game_if_the_game_is_over()
    {
        /** @var Game $game */
        $game = factory(Game::class)->create();
        $homeTeam = $game->homeTeam;

        $player = factory(Player::class)->create([
            'team_id' => $homeTeam->id
        ]);

        $sport = $homeTeam->league->sport;

        $statTypes = $sport->statTypes()->inRandomOrder()->take(rand(1,5))->get();

        $statAmountDTOs = $statTypes->map(function (StatType $statType) {
            return new StatAmountDTO($statType,rand(1,50)/2);
        });

        $playerDTO = new PlayerGameLogDTO($player, $game, $homeTeam, $statAmountDTOs);

        $gameLogDTOs = new GameLogDTOCollection([$playerDTO]);
        $gameLogDTOs->setGameOver(true);

        $mockIntegration = new MockIntegration(null,null,null, $gameLogDTOs);
        app()->instance(StatsIntegration::class, $mockIntegration);

        /** @var BuildPlayerGameLogsForGameAction $domainAction */
        $domainAction = app(BuildPlayerGameLogsForGameAction::class);
        $domainAction->execute($game);

        $game = $game->fresh();
        $this->assertNotNull($game->finalized_at);
    }
}
