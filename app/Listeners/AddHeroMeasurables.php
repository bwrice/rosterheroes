<?php

namespace App\Listeners;

use App\StorableEvents\HeroCreated;
use App\Domain\Models\MeasurableType;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddHeroMeasurables
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  HeroCreated $event
     * @return void
     */
    public function handle(HeroCreated $event)
    {
        $measurableTypes = MeasurableType::heroTypes();

        $hero = $event->hero;

        $measurableTypes->each(function (MeasurableType $measurableType) use ($hero) {
           $hero->measurables()->create([
               'measurable_type_id' => $measurableType->id,
               'amount_raised' => 0
           ]);
        });
    }
}
