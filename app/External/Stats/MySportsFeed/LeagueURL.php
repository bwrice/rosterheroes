<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/17/19
 * Time: 9:52 PM
 */

namespace App\External\Stats\MySportsFeed;


use App\Domain\Models\League;

class LeagueURL
{
    /**
     * @param League $league
     * @return string
     */
    public function getTeamsURL(League $league)
    {
        switch ($league->abbreviation) {
            case League::NFL:
                return 'nfl/2018-regular/team_stats_totals.json';
            case League::MLB:
                return 'mlb/2018-regular/team_stats_totals.json';
            case League::NBA:
                return 'nba/2018-2019-regular/team_stats_totals.json';
            case League::NHL:
                return 'nhl/2018-2019-regular/team_stats_totals.json';
            default:
                throw new \RuntimeException("Unknown League with ID: " . $league->id);
        }
    }

    public function getPlayersURL(League $league)
    {
        switch ($league->abbreviation) {
            case League::NFL:
                return 'nfl/players.json';
            case League::MLB:
                return 'mlb/players.json';
            case League::NBA:
                return 'nba/players.json';
            case League::NHL:
                return 'nhl/players.json';
            default:
                throw new \RuntimeException("Unknown League with ID: " . $league->id);
        }
    }
}