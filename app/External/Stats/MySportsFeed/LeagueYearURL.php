<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/24/19
 * Time: 10:29 PM
 */

namespace App\External\Stats\MySportsFeed;


use App\League;

class LeagueYearURL
{
    /**
     * @param string $league
     * @return string
     */
    public function getURL(string $league)
    {
        // TODO make these dynamic based on time of year
        switch ($league) {
            case League::NFL:
                return 'nfl/2018-regular';
            case League::MLB:
                return 'mlb/2018-regular';
            case League::NBA:
                return 'nba/2018-2019-regular';
            case League::NHL:
                return 'nhl/2018-2019-regular';
            default:
                throw new \RuntimeException("Cannot get year URL for uknown league: " . $league);
        }
    }


    /**
     * @return array
     */
    public function getSubURLs()
    {
        return [
            League::NFL => $this->getURL(League::NFL),
            League::MLB => $this->getURL(League::MLB),
            League::NBA => $this->getURL(League::NBA),
            League::NHL => $this->getURL(League::NHL)
        ];
    }
}