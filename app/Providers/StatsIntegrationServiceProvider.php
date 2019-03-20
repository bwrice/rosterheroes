<?php

namespace App\Providers;

use App\External\Stats\MySportsFeed\MySportsFeed;
use App\External\Stats\MySportsFeed\LeagueURL;
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
            return new MySportsFeed(new Client(), new LeagueURL());
        });
    }

}
