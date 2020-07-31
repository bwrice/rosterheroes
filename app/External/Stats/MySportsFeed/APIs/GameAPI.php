<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/24/19
 * Time: 9:58 PM
 */

namespace App\External\Stats\MySportsFeed\APIs;


use App\Domain\Models\League;
use App\External\Stats\MySportsFeed\LeagueSeasonConverter;
use App\External\Stats\MySportsFeed\MSFClient;

class GameAPI
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
        $regularSeasonGames = $this->getGames($league, $yearDelta, true);
        $postSeasonGames = $this->getGames($league, $yearDelta, false);
        return array_merge($regularSeasonGames, $postSeasonGames);
    }

    protected function getGames(League $league, int $yearDelta, $regularSeason = true)
    {
        $season = $this->leagueSeasonConverter->getSeason($league, $yearDelta, $regularSeason);
        $subURL = strtolower($league->abbreviation) . '/'. $season;
        $subURL .= $regularSeason ? '-regular' : '-playoff';
        $subURL .= '/games.json';
        $responseData = $this->client->getData($subURL);
        return $responseData['games'];
    }
}
