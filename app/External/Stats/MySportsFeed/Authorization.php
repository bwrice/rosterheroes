<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/21/19
 * Time: 8:08 PM
 */

namespace App\External\Stats\MySportsFeed;


class Authorization
{
    const PASSWORD = 'MYSPORTSFEEDS';

    protected function getAPIKey()
    {
        return config('services.mysportsfeed')['key'];
    }

    public function getAuthorization()
    {
        return [
            $this->getAPIKey(),
            self::PASSWORD
        ];
    }
}