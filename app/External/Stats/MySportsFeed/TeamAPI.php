<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/20/19
 * Time: 10:07 PM
 */

namespace App\External\Stats\MySportsFeed;

use App\League;

class TeamAPI
{
    /**
     * @var MSFClient
     */
    private $client;

    public function __construct(MSFClient $client)
    {
        $this->client = $client;
    }

    public function getData()
    {
        $data = [];
        foreach($this->getLeagueSubURLs() as $league => $subURL) {
            $url = $subURL . '/team_stats_totals.json';
            $leagueData = $this->client->getData($url);
            $data[$league] = $leagueData['teamStatsTotals'];
        }
        return $data;
    }

    protected function getLeagueSubURLs()
    {
        // TODO make these dynamic based on time of year
        return [
            League::NFL => 'nfl/2018-regular',
            League::MLB => 'mlb/2018-regular',
            League::NBA => 'nba/2018-2019-regular',
            League::NHL => 'nhl/2018-2019-regular'
        ];
    }
}