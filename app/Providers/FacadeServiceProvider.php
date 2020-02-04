<?php

namespace App\Providers;

use App\Services\Combat;
use App\Services\CurrentWeek;
use App\Services\FantasyPower;
use App\Services\ModelServices\AttackService;
use App\Services\ModelServices\HeroService;
use App\Services\ModelServices\SideQuestService;
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
        $this->app->bind('side-quest-service', function () {
            return new SideQuestService();
        });
        $this->app->bind('fantasy-power', function () {
            return new FantasyPower();
        });
        $this->app->bind('combat', function () {
            return new Combat();
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
