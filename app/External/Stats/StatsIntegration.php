<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/12/19
 * Time: 9:23 PM
 */

namespace App\External\Stats;


interface StatsIntegration
{
    public function getPlayerDTOs(): PlayerDTOCollection;
}