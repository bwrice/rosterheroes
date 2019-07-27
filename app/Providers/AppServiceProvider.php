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
use Spatie\EventProjector\Projectionist;

class AppServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->setTheCurrentWeek();

        Date::use(CarbonImmutable::class);
    }

    protected function setTheCurrentWeek()
    {
        if (Schema::hasTable('weeks')) {
            Week::setCurrent(Week::query()->orderBy('ends_at')->whereNull('finalized_at')->first());
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
