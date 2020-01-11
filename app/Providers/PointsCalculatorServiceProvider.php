<?php

namespace App\Providers;

use App\Domain\Behaviors\StatTypes\Baseball\InningPitchedBehavior;
use App\Domain\Behaviors\StatTypes\InningsPitchedCalculator;
use App\Domain\Behaviors\StatTypes\MultiplierCalculator;
use App\Domain\Behaviors\StatTypes\PointsCalculator;
use Illuminate\Support\ServiceProvider;

class PointsCalculatorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(InningPitchedBehavior::class)
            ->needs(PointsCalculator::class)
            ->give(function () {
                return new InningsPitchedCalculator(new MultiplierCalculator());
            });
        $this->app->bind(PointsCalculator::class, function ($app) {
            return new MultiplierCalculator();
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
