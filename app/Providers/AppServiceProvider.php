<?php

namespace App\Providers;

use App\Projectors\ItemBlueprintProjector;
use Illuminate\Support\ServiceProvider;
use Spatie\EventProjector\Projectionist;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Projectionist $projectionist)
    {
        $projectionist->addProjectors([
            ItemBlueprintProjector::class
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
