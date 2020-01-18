<?php

namespace App\Providers;

use App\Services\CurrentWeek;
use App\Services\HeroCombat;
use App\Services\ModelServices\WeekService;
use Illuminate\Support\ServiceProvider;

class FacadeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('current-week', function () {
            return new CurrentWeek();
        });
        $this->app->bind('hero-combat', function () {
            return new HeroCombat();
        });
        $this->app->bind('week-service', function () {
            return new WeekService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
