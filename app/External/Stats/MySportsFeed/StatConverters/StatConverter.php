<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/20/19
 * Time: 11:42 PM
 */

namespace App\External\Stats\MySportsFeed\StatConverters;


use App\Domain\Collections\PlayerStatCollection;

interface StatConverter
{
    public function getStats(array $statsData): PlayerStatCollection;
}