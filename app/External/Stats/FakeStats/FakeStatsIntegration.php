<?php


namespace App\External\Stats\FakeStats;


use App\Domain\Collections\GameLogDTOCollection;
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
    /**
     * @var BuildFakePlayerGameLogDTO
     */
    private $buildFakePlayerGameLogDTO;

    public function __construct(BuildFakePlayerGameLogDTO $buildFakePlayerGameLogDTO)
    {
        $this->buildFakePlayerGameLogDTO = $buildFakePlayerGameLogDTO;
    }

    public function getPlayerDTOs(League $league): Collection
    {
        return collect();
    }

    public function getTeamDTOs(League $league, int $yearDelta): Collection
    {
        return collect();
    }

    public function getGameDTOs(League $league, int $yearDelta): Collection
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
            'playerGameLogs.playerStats'
        ])->chunk(50, function(Collection $players) use (&$gameLogDTOs, $team, $game) {
            $gameLogDTOs = $gameLogDTOs->merge($players->map(function (Player $player) use ($team, $game) {
                return $this->buildFakePlayerGameLogDTO->execute($player, $team, $game);
            })->values());
        });
        return $gameLogDTOs;
    }
}
