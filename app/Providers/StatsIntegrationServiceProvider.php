<?php

namespace App\Providers;

use App\External\Stats\FakeStats\CreateFakeStatAmountDTOsForPlayer;
use App\External\Stats\FakeStats\FakeStatsIntegration;
use App\External\Stats\MySportsFeed\MySportsFeed;
use App\External\Stats\StatsIntegration;
use Illuminate\Support\ServiceProvider;
class StatsIntegrationServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->app->bind(StatsIntegration::class, function ($app) {
            if (config('stats-integration.driver') === FakeStatsIntegration::ENV_KEY) {
                return app(FakeStatsIntegration::class);
            }
            return app(MySportsFeed::class);
        });
    }

}
