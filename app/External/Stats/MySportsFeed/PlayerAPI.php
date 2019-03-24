<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/20/19
 * Time: 10:06 PM
 */

namespace App\External\Stats\MySportsFeed;

class PlayerAPI
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
        foreach($this->getURLs() as $league) {
            $url = $league . '/players.json';
            $leagueData = $this->client->getData($url);
            $data = array_merge($data, $leagueData['players']);
        }
        return $data;
    }

    protected function getURLs()
    {
        return [
            'nfl',
            'mlb',
            'nba',
            'nhl'
        ];
    }
}