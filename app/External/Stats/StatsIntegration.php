<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/12/19
 * Time: 9:23 PM
 */

namespace App\External\Stats;

use Illuminate\Support\Collection;

interface StatsIntegration
{
    public function getPlayerDTOs(): Collection;

    public function getTeamDTOs(): Collection;

    public function getGameDTOs(): Collection;
}