<?php

namespace App\Providers;

use App\Domain\Models\Hero;
use App\Domain\Models\Measurable;
use App\Policies\HeroPolicy;
use App\Policies\MeasurablePolicy;
use App\Policies\SquadPolicy;
use App\Domain\Models\Squad;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Squad::class => SquadPolicy::class,
        Hero::class => HeroPolicy::class,
        Measurable::class => MeasurablePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
