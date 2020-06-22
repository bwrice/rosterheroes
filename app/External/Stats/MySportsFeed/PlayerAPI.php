<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/20/19
 * Time: 10:06 PM
 */

namespace App\External\Stats\MySportsFeed;

use App\Domain\Models\League;

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

    public function getData(League $league, $queryArgs = [])
    {
        $subURL = $league->abbreviation . '/players.json';
        $responseData = $this->client->getData($subURL, $queryArgs);
        return $responseData['players'];
    }
}
