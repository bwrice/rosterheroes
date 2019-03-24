<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/23/19
 * Time: 9:56 AM
 */

namespace App\External\Stats\MySportsFeed;


use GuzzleHttp\Client;

class MSFClient
{
    const BASE_URL = 'https://api.mysportsfeeds.com/v2.0/pull/';

    /**
     * @var Client
     */
    private $client;
    /**
     * @var Authorization
     */
    private $authorization;

    public function __construct(Client $client, Authorization $authorization)
    {
        $this->client = $client;
        $this->authorization = $authorization;
    }

    /**
     * @param $url
     * @return mixed
     */
    public function getData($url)
    {
        $fullURL = self::BASE_URL . $url;
        $response = $this->getResponse($fullURL);
        return json_decode($response->getBody(), true);
    }

    protected function getResponse(string $fullURL)
    {
        return $this->client->get($fullURL, [
            'auth' => $this->authorization->getAuthorization()
        ]);
    }
}