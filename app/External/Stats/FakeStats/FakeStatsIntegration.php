<?php


namespace App\External\Stats\FakeStats;


use App\Domain\Collections\GameLogDTOCollection;
use App\Domain\DataTransferObjects\PlayerGameLogDTO;
use App\Domain\Models\Game;
use App\Domain\Models\League;
use App\Domain\Models\Player;
use App\Domain\Models\StatsIntegrationType;
use App\Domain\Models\Team;
use App\External\Stats\StatsIntegration;
use Illuminate\Support\Collection;

class FakeStatsIntegration implements StatsIntegration
{
    const INTEGRATION_NAME = 'fake-stats-integration';
    const ENV_KEY = 'fake';

    /**
     * @var CreateFakeStatAmountDTOsForPlayer
     */
    private $createFakeStatAmountDTOsForPlayer;

    public function __construct(CreateFakeStatAmountDTOsForPlayer $createFakeStatAmountDTOsForPlayer)
    {
        $this->createFakeStatAmountDTOsForPlayer = $createFakeStatAmountDTOsForPlayer;
    }

    public function getPlayerDTOs(League $league): Collection
    {
        return collect();
    }

    public function getTeamDTOs(League $league, int $yearDelta): Collection
    {
        return collect();
    }

    public function getGameDTOs(League $league, int $yearDelta, bool $regularSeason): Collection
    {
        return collect();
    }

    public function getIntegrationType(): StatsIntegrationType
    {
        /** @var StatsIntegrationType $integrationType */
        $integrationType = StatsIntegrationType::query()->firstOrCreate([
            'name' => self::INTEGRATION_NAME
        ]);
        return $integrationType;
    }

    public function getGameLogDTOs(Game $game, int $yearDelta): GameLogDTOCollection
    {
        $gameLogDTOs = $this->getGameLogDTOsForTeam($game->homeTeam, $game);
        $gameLogDTOs = $gameLogDTOs->merge($this->getGameLogDTOsForTeam($game->awayTeam, $game));

        if ($game->starts_at->addHours(3)->isPast()) {
            $gameLogDTOs->setGameOver(true);
        }

        return $gameLogDTOs;
    }

    protected function getGameLogDTOsForTeam(Team $team, Game $game)
    {
        $gameLogDTOs = new GameLogDTOCollection();
        $team->players()->with([
            'positions',
            'playerGameLogs.playerStats',
            'playerGameLogs.game'
        ])->chunk(10, function(Collection $players) use (&$gameLogDTOs, $team, $game) {
            $gameLogDTOs = $gameLogDTOs->merge($players->map(function (Player $player) use ($team, $game) {
                $statAmountDTOs = $this->createFakeStatAmountDTOsForPlayer->execute($player);
                return new PlayerGameLogDTO(
                    $player,
                    $game,
                    $team,
                    $statAmountDTOs
                );
            })->values());
        });
        return $gameLogDTOs;
    }
}
