<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/12/19
 * Time: 9:23 PM
 */

namespace App\External\Stats;

use App\Domain\Models\League;
use App\Domain\Models\Week;
use Illuminate\Support\Collection;

interface StatsIntegration
{
    public function getPlayerDTOs(): Collection;

    public function getTeamDTOs(League $league): Collection;

    public function getGameDTOs(Week $week): Collection;
}