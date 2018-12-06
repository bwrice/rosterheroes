<?php

namespace App\Providers;

use App\Hero;
use App\Wagons\Wagon;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class RelationsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'wagons' => Wagon::class,
            'heroes' => Hero::class
        ]);
    }
}
