<?php

namespace App\Providers;

use App\Domain\Enums\Enum;
use Illuminate\Support\ServiceProvider;

class EnumServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('enum', function() {
            return new Enum();
        });
    }
}
