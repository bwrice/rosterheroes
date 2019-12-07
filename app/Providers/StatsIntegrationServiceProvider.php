<?php

namespace App\Providers;

use App\External\Stats\MySportsFeed\MySportsFeed;
use App\External\Stats\StatsIntegration;
use Illuminate\Support\ServiceProvider;
class StatsIntegrationServiceProvider extends ServiceProvider
{

    public function boot()
    {
        // Bind MySportsFeed to container as implementation of StatsIntegration interface
        $this->app->bind(StatsIntegration::class, function ($app) {
            return app(MySportsFeed::class);
        });
    }

}
