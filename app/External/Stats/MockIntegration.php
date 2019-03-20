<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/16/19
 * Time: 5:38 PM
 */

namespace App\External\Stats;

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

    public function __construct(Collection $teamDTOs = null, Collection $playerDTOs = null)
    {
        $this->teamDTOs = $teamDTOs;
        $this->playerDTOs = $playerDTOs;
    }


    public function getPlayerDTOs(): Collection
    {
        return $this->playerDTOs ?: collect();
    }

    public function getTeamDTOs(): Collection
    {
        return $this->teamDTOs ?: collect();
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
}