<?php

namespace App\Providers;

use App\Services\CurrentWeek;
use App\Services\ModelServices\HeroService;
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
        $this->app->bind('hero-service', function () {
            return new HeroService();
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
