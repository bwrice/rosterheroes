<?php

namespace App\Listeners;

use App\Events\WagonEvent;
use App\Domain\Models\SlotType;
use App\Wagons\Wagon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddWagonSlots
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
     * @param WagonEvent $event
     * @return void
     */
    public function handle(WagonEvent $event)
    {
        $wagon = $event->wagon;
        $slotsNeededCount = $wagon->wagonSize->getBehavior()->getTotalSlotsCount();
        $currentSlotsCount = $wagon->slots()->count();
        $diff = $slotsNeededCount - $currentSlotsCount;

        if($diff > 0) {
            /** @var \App\Domain\Models\SlotType $slotType */
            $slotType = SlotType::where('name', '=', SlotType::UNIVERSAL)->first();
            for($i = 1; $i <= $diff; $i++) {
                $wagon->slots()->create([
                    'slot_type_id' => $slotType->id
                ]);
            }
        }
    }
}
