<?php

namespace App\Providers;

use App\Exceptions\NoCurrentWeekException;
use App\Projectors\ItemBlueprintProjector;
use App\Domain\Models\Week;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Spatie\EventSourcing\Projectionist;

class AppServiceProvider extends ServiceProvider
{

    public function boot()
    {
        Date::use(CarbonImmutable::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment(['local', 'staging'])) {
            $this->app->register(TelescopeServiceProvider::class);
        }
    }
}
