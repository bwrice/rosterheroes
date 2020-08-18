<?php

namespace App\Providers;

use App\Services\Admin;
use App\Services\ContentService;
use App\Services\CurrentWeek;
use App\Services\ModelServices\AttackService;
use App\Services\ModelServices\HeroService;
use App\Services\ModelServices\SquadService;
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
        $this->app->bind('squad-service', function () {
            return new SquadService();
        });
        $this->app->bind('hero-service', function () {
            return new HeroService();
        });
        $this->app->bind('week-service', function () {
            return new WeekService();
        });
        $this->app->bind('attack-service', function () {
            return new AttackService();
        });
        $this->app->bind('admin', function () {
            return new Admin();
        });
        $this->app->bind('content', function () {
            return new ContentService();
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
