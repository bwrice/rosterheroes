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
        $this->setTheCurrentWeek();

        Date::use(CarbonImmutable::class);
    }

    protected function setTheCurrentWeek()
    {
        if (Schema::hasTable('weeks') && Schema::hasColumn('weeks', 'made_current_at')) {
            Week::setCurrent(Week::query()->current());
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
