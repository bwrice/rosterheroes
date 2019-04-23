<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/16/19
 * Time: 5:38 PM
 */

namespace App\External\Stats;

use App\Domain\Models\League;
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

    public function __construct(Collection $teamDTOs = null, Collection $playerDTOs = null, Collection $gameDTOs = null)
    {
        $this->teamDTOs = $teamDTOs;
        $this->playerDTOs = $playerDTOs;
        $this->gameDTOs = $gameDTOs;
    }


    public function getPlayerDTOs(League $league): Collection
    {
        return $this->playerDTOs ?: collect();
    }

    public function getTeamDTOs(League $league): Collection
    {
        return $this->teamDTOs ?: collect();
    }

    public function getGameDTOs(League $league): Collection
    {
        return $this->gameDTOs;
    }

    /**
     * @param Collection $teamDTOs
     * @return MockIntegration
     */
    public function setTeamDTOs(Collection $teamDTOs): MockIntegration
    {
        $this->teamDTOs = $teamDTOs;
        return $this;
    }

    /**
     * @param Collection $playerDTOs
     * @return MockIntegration
     */
    public function setPlayerDTOs(Collection $playerDTOs): MockIntegration
    {
        $this->playerDTOs = $playerDTOs;
        return $this;
    }

    /**
     * @param Collection $gameDTOs
     * @return MockIntegration
     */
    public function setGameDTOs(Collection $gameDTOs): MockIntegration
    {
        $this->gameDTOs = $gameDTOs;
        return $this;
    }
}