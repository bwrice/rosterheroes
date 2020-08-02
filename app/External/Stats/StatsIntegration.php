<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/12/19
 * Time: 9:23 PM
 */

namespace App\External\Stats;

use App\Domain\Collections\GameLogDTOCollection;
use App\Domain\Models\League;
use App\Domain\Models\Team;
use App\Domain\Models\Game;
use App\Domain\Models\StatsIntegrationType;
use Illuminate\Support\Collection;
use App\Domain\Collections\PositionCollection;

interface StatsIntegration
{
    public function getPlayerDTOs(League $league): Collection;

    public function getTeamDTOs(League $league, int $yearDelta): Collection;

    public function getGameDTOs(League $league, int $yearDelta, bool $regularSeason): Collection;

    public function getGameLogDTOs(Game $game, int $yearDelta): GameLogDTOCollection;

    public function getIntegrationType(): StatsIntegrationType;
}
