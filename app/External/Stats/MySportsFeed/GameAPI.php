<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/24/19
 * Time: 9:58 PM
 */

namespace App\External\Stats\MySportsFeed;


use App\Domain\Models\League;

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

    public function getData(League $league)
    {
        $season = $this->leagueSeasonConverter->getSeason($league);
        $subURL = $season . '/games.json';
        $responseData = $this->client->getData($subURL);
        return $responseData['games'];
    }
}