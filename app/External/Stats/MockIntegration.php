<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/16/19
 * Time: 5:38 PM
 */

namespace App\External\Stats;

use App\Domain\Collections\GameLogDTOCollection;
use App\Domain\Collections\PositionCollection;
use App\Domain\Models\Game;
use App\Domain\Models\League;
use App\Domain\Models\Team;
use App\Domain\Models\StatsIntegrationType;
use Illuminate\Support\Collection;

class MockIntegration implements StatsIntegration
{
    public const INTEGRATION_NAME = 'mock-integration';

    /**
     * @var Collection
     */
    private $teamDTOs;
    /**
     * @var Collection
     */
    private $playerDTOs;
    /**
     * @var Collection
     */
    private $gameDTOs;
    /**
     * @var Collection
     */
    private $playerGameLogDTOs;

    public function __construct(
        Collection $teamDTOs = null,
        Collection $playerDTOs = null,
        Collection $gameDTOs = null,
        GameLogDTOCollection $playerGameLogDTOs = null)
    {
        $this->teamDTOs = $teamDTOs;
        $this->playerDTOs = $playerDTOs;
        $this->gameDTOs = $gameDTOs;
        $this->playerGameLogDTOs = $playerGameLogDTOs;
    }


    public function getPlayerDTOs(League $league): Collection
    {
        return $this->playerDTOs ?: collect();
    }

    public function getTeamDTOs(League $league, int $yearDelta): Collection
    {
        return $this->teamDTOs ?: collect();
    }

    public function getGameDTOs(League $league, int $yearDelta = 0, bool $regularSeason = true): Collection
    {
        return $this->gameDTOs ?: collect();
    }

    public function getGameLogDTOs(Game $game, int $yearDelta): GameLogDTOCollection
    {
        return $this->playerGameLogDTOs ?: new GameLogDTOCollection();
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
