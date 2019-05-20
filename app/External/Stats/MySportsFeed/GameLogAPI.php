<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/19/19
 * Time: 8:29 AM
 */

namespace App\External\Stats\MySportsFeed;


class GameLogAPI
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