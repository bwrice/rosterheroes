<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/12/19
 * Time: 9:23 PM
 */

namespace App\External\Stats;

use App\Domain\Models\League;
use App\Domain\Models\Team;
use App\Domain\Models\Game;
use Illuminate\Support\Collection;

interface StatsIntegration
{
    public function getPlayerDTOs(League $league): Collection;

    public function getTeamDTOs(League $league, int $yearDelta): Collection;

    public function getGameDTOs(League $league, int $yearDelta): Collection;

    public function getPlayerGameLogDTOs(Game $game, int $yearDelta): Collection;

    public function getIntegrationName(): string;
}
