<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/20/19
 * Time: 11:37 PM
 */

namespace App\External\Stats\MySportsFeed\StatConverters;


use App\Domain\Models\League;

class StatConverterFactory
{
    /**
     * @param League $league
     * @return StatConverter
     */
    public function getStatConverter(League $league): StatConverter
    {
        switch($league->abbreviation) {
            case League::NFL:
                return new NFLStatConverter();
            case League::MLB:
                return new MLBStatConverter();
            case League::NBA:
                return new NBAStatConverter();
            case League::NHL:
                return new NHLStatConverter();
        }
        throw new \InvalidArgumentException("Unknown League: " . $league->abbreviation);
    }
}