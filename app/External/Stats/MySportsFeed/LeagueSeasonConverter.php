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
     * @param bool $regularSeason
     * @return string
     */
    public function getSeason(League $league, int $yearDelta, $regularSeason = true)
    {
        switch($league->abbreviation) {
            case League::NHL:
            case League::NBA:
                // NHL and NBA regular seasons span multiple years, so we need to return both
                if ($regularSeason) {
                    return ($league->getBehavior()->getSeason() + $yearDelta) . '-' . ($league->getBehavior()->getSeason() + 1 + $yearDelta);
                } else {
                    return (string) ($league->getBehavior()->getSeason() + 1 + $yearDelta);
                }
            case League::NFL:
                if ($regularSeason) {
                    return (string) ($league->getBehavior()->getSeason() + $yearDelta);
                } else {
                    return (string) ($league->getBehavior()->getSeason() + 1 + $yearDelta);
                }
            case League::MLB:
                return (string) ($league->getBehavior()->getSeason() + $yearDelta);
        }
        throw new UnknownLeagueException($league);
    }
}
