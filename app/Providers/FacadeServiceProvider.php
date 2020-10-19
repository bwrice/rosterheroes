<?php

namespace App\Providers;

use App\Services\Admin;
use App\Services\ContentService;
use App\Services\CurrentWeek;
use App\Services\Models\AttackService;
use App\Services\Models\HeroService;
use App\Services\Models\Reference\CombatPositionService;
use App\Services\Models\Reference\DamageTypeService;
use App\Services\Models\Reference\ItemTypeService;
use App\Services\Models\Reference\TargetPriorityService;
use App\Services\Models\SquadService;
use App\Services\Models\WeekService;
use App\Services\NPCService;
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
        $this->app->bind('current-week', fn() => new CurrentWeek());
        $this->app->bind('squad-service', fn() => new SquadService());
        $this->app->bind('hero-service', fn() => new HeroService());
        $this->app->bind('week-service', fn() => new WeekService());
        $this->app->bind('attack-service', fn() => new AttackService());
        $this->app->bind('admin', fn() => new Admin());
        $this->app->bind('content', fn() => new ContentService());
        $this->app->bind('npc', fn() => new NPCService());
        $this->app->bind(DamageTypeService::class, fn() => new DamageTypeService());
        $this->app->bind(TargetPriorityService::class, fn() => new TargetPriorityService());
        $this->app->bind(CombatPositionService::class, fn() => new CombatPositionService());
        $this->app->bind(ItemTypeService::class, fn() => new ItemTypeService());
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
