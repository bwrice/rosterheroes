<?php

namespace App\Providers;

use App\External\Stats\MySportsFeedIntegration;
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
            return new MySportsFeedIntegration(new Client());
        });
    }

}
