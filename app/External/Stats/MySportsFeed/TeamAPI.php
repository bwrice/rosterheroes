<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/20/19
 * Time: 10:07 PM
 */

namespace App\External\Stats\MySportsFeed;

use App\Domain\Models\League;

class TeamAPI
{
    /**
     * @var MSFClient
     */
    private $client;
    /**
     * @var LeagueSeasonConverter
     */
    private $leagueSeasonConverter;

    public function __construct(MSFClient $client, LeagueSeasonConverter $leagueSeasonConverter)
    {
        $this->client = $client;
        $this->leagueSeasonConverter = $leagueSeasonConverter;
    }

    public function getData(League $league)
    {
        $season = $this->leagueSeasonConverter->getSeason($league);
        $subURL = $season . '/team_stats_totals.json';
        $responseData = $this->client->getData($subURL);
        return $responseData['teamStatsTotal'];
    }
}