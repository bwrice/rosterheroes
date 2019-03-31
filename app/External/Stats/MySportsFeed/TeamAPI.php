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
            $url = $subURL . '/team_stats_totals.json';
            $leagueData = $this->client->getData($url);
            $data[$league] = $leagueData['teamStatsTotals'];
        }
        return $data;
    }
}