<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/16/19
 * Time: 5:38 PM
 */

namespace App\External\Stats;

use App\Domain\Models\League;
use App\Domain\Models\Team;
use Illuminate\Support\Collection;

class MockIntegration implements StatsIntegration
{
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
        Collection $playerGameLogDTOs = null)
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

    public function getGameDTOs(League $league, int $yearDelta): Collection
    {
        return $this->gameDTOs ?: collect();
    }

    public function getHistoricPlayerGameLogDTOs(Team $team, int $yearDelta): Collection
    {
        return $this->playerGameLogDTOs ?: collect();
    }
}
