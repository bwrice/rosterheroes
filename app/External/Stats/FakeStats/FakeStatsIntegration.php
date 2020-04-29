<?php


namespace App\External\Stats\FakeStats;


use App\Domain\Collections\GameLogDTOCollection;
use App\Domain\Models\Game;
use App\Domain\Models\League;
use App\Domain\Models\Player;
use App\Domain\Models\StatsIntegrationType;
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

    public function getGameLogDTOs(Game $game, int $yearDelta): GameLogDTOCollection
    {
        $gameLogDTOs = new GameLogDTOCollection();
        $game->homeTeam->players->each(function (Player $player) use ($gameLogDTOs) {
            $gameLogDTOs->push($this->buildFakePlayerGameLogDTO->execute());
        });
        $game->awayTeam->players->each(function (Player $player) use ($gameLogDTOs) {
            $gameLogDTOs->push($this->buildFakePlayerGameLogDTO->execute());
        });

        return $gameLogDTOs;
    }

    public function getIntegrationType(): StatsIntegrationType
    {
        /** @var StatsIntegrationType $integrationType */
        $integrationType = StatsIntegrationType::query()->firstOrCreate([
            'name' => self::INTEGRATION_NAME
        ]);
        return $integrationType;
    }
}
