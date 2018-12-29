<?php

namespace App\Listeners;

use App\Events\HeroCreated;
use App\Events\HeroEvent;
use App\Hero;
use App\SlotType;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddHeroSlots
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
     * @param HeroCreated $event
     */
    public function handle(HeroCreated $event)
    {
        $hero = $event->hero;
        $heroSlotTypes = SlotType::heroTypes();

        $heroSlotTypes->each(function (SlotType $slotType) use ($hero) {
            $hero->slots()->create([
                'slot_type_id' => $slotType->id
            ]);
        });
    }
}
