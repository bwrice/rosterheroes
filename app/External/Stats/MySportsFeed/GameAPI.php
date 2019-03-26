<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/24/19
 * Time: 9:58 PM
 */

namespace App\External\Stats\MySportsFeed;


class GameAPI
{
    /**
     * @var MSFClient
     */
    private $client;
    /**
     * @var LeagueYearURL
     */
    private $leagueYearURL;

    public function __construct(MSFClient $client, LeagueYearURL $leagueYearURL)
    {
        $this->client = $client;
        $this->leagueYearURL = $leagueYearURL;
    }

    public function getData()
    {
        $data = [];
        foreach($this->leagueYearURL->getSubURLs() as $league => $subURL) {
            $url = $subURL . '/games.json';
            $leagueData = $this->client->getData($url);
            $data[$league] = $leagueData['games'];
        }
        return $data;
    }
}