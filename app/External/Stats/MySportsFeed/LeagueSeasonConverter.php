<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 4/14/19
 * Time: 1:28 PM
 */

namespace App\External\Stats\MySportsFeed;


use App\Domain\Models\League;
use App\Exceptions\UnknownLeagueException;

class LeagueSeasonConverter
{
    /**
     * @param League $league
     * @param int $yearDelta
     * @return string
     */
    public function getSeason(League $league, int $yearDelta = 0)
    {
        switch($league->abbreviation) {
            case League::NHL:
            case League::NBA:
                return ($league->getBehavior()->getSeason() + $yearDelta) . '-' . ($league->getBehavior()->getSeason() + 1 + $yearDelta);
            case League::NFL:
            case League::MLB:
                return (string) ($league->getBehavior()->getSeason() + $yearDelta);
        }
        throw new UnknownLeagueException($league);
    }
}