<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/20/19
 * Time: 10:07 PM
 */

namespace App\External\Stats\MySportsFeed\APIs;

use App\Domain\Models\League;
use App\External\Stats\MySportsFeed\LeagueSeasonConverter;
use App\External\Stats\MySportsFeed\MSFClient;

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

    public function getData(League $league, int $yearDelta = 0)
    {
        $season = $this->leagueSeasonConverter->getSeason($league, $yearDelta);
        $subURL = strtolower($league->abbreviation) . '/'. $season . '-regular/team_stats_totals.json';
        $responseData = $this->client->getData($subURL);
        return $responseData['teamStatsTotals'];
    }
}
