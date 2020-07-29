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
        $season = $this->leagueSeasonConverter->getSeason($league, $yearDelta);
        $subURL = strtolower($league->abbreviation) . '/'. $season . '-regular/games.json';
        $responseData = $this->client->getData($subURL);
        return $responseData['games'];
    }
}
