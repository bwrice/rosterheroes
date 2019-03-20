<?php

namespace App\Providers;

use App\Exceptions\NoCurrentWeekException;
use App\Projectors\ItemBlueprintProjector;
use App\Weeks\Week;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Spatie\EventProjector\Projectionist;

class AppServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->setTheCurrentWeek();
    }

    protected function setTheCurrentWeek()
    {
        if (Schema::hasTable('weeks')) {
            Week::setCurrent(Week::query()->orderBy('ends_at')->whereNull('finalized_at')->first());

            if (Week::current() == null) {
                Log::critical("There is no current week");
            }
        }
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
