<?php

namespace App\Providers;

use App\Events\HeroCreated;
use App\Events\SquadCreated;
use App\Events\WagonCreated;
use App\Listeners\AddHeroMeasurables;
use App\Listeners\AddHeroSlots;
use App\Listeners\AddSquadSlots;
use App\Listeners\AddSquadStartingGold;
use App\Listeners\AddWagonSlots;
use App\Listeners\CreateNewWagon;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        SquadCreated::class => [
            AddSquadSlots::class
        ],
        HeroCreated::class => [
            AddHeroSlots::class,
            AddHeroMeasurables::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
