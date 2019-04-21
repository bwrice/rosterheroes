<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/12/19
 * Time: 9:23 PM
 */

namespace App\External\Stats;

use App\Domain\Models\League;
use Illuminate\Support\Collection;

interface StatsIntegration
{
    public function getPlayerDTOs(League $league): Collection;

    public function getTeamDTOs(League $league): Collection;

    public function getGameDTOs(League $league): Collection;
}