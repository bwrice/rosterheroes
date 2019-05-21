<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/20/19
 * Time: 11:52 PM
 */

namespace App\External\Stats\MySportsFeed\StatConverters;


use App\Domain\Collections\PlayerStatCollection;

class NBAStatConverter implements StatConverter
{

    public function getStats(array $statsData): PlayerStatCollection
    {
        // TODO: Implement getStats() method.
    }
}