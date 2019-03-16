<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/13/19
 * Time: 9:08 PM
 */

namespace App\External\Stats;

use GuzzleHttp\Client;

class MySportsFeedIntegration
{
    const CONFIG_KEY = 'services.mysportsfeed';
    const PASSWORD = 'MYSPORTSFEEDS';
    const BASE_URL = 'https://api.mysportsfeeds.com/v2.0/pull/';
    /**
     * @var Client
     */
    private $guzzleClient;

    public function __construct(Client $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }

    protected function getAPIKey()
    {
        return config(self::CONFIG_KEY)['key'];
    }


    public function getPlayerDTOs()
    {
        $playerDTOs = new PlayerDTOCollection();

    }

    public function getNFLPlayers()
    {
        $authorization = $this->getAuthorization();
        try {
            $data = $this->guzzleClient->get( self::BASE_URL . 'nfl/players.json', [
                'auth' => [
                    $this->getAPIKey(),
                    self::PASSWORD
                ]
            ]);
        } catch (\Exception $e) {
            return $e;
        }
        return $data;
    }

    protected function getAuthorization()
    {
        $key = $this->getAPIKey();
        return 'Basic ' . base64_encode($key . ":" . self::PASSWORD);
    }

    protected function getRequest()
    {

    }
}