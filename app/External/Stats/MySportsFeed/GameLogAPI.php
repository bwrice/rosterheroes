<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 5/19/19
 * Time: 8:29 AM
 */

namespace App\External\Stats\MySportsFeed;


use App\Domain\Models\League;
use App\Domain\Models\Team;

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

    public function getData(Team $team, int $yearDelta = 0)
    {
        $season = $this->leagueSeasonConverter->getSeason($team->league, $yearDelta);
        $subURL = $season . '/player_gamelogs.json?team=' . strtolower($team->abbreviation);
        $responseData = $this->client->getData($subURL);
        return $responseData['gamelogs'];
    }
}