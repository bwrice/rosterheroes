<?php

namespace App\Providers;

use App\External\Stats\MySportsFeed\Authorization;
use App\External\Stats\MySportsFeed\GameAPI;
use App\External\Stats\MySportsFeed\LeagueYearURL;
use App\External\Stats\MySportsFeed\MSFClient;
use App\External\Stats\MySportsFeed\MySportsFeed;
use App\External\Stats\MySportsFeed\LeagueURL;
use App\External\Stats\MySportsFeed\PlayerAPI;
use App\External\Stats\MySportsFeed\TeamAPI;
use App\External\Stats\StatsIntegration;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
class StatsIntegrationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(StatsIntegration::class, function ($app) {
            return new MySportsFeed(
                new PlayerAPI(new MSFClient(new Client(), new Authorization())),
                new TeamAPI(new MSFClient(new Client(), new Authorization()), new LeagueYearURL()),
                new GameAPI(New MSFClient(new Client(), new Authorization()), new LeagueYearURL())
            );
        });
    }

}
