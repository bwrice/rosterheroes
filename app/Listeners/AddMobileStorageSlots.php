<?php

namespace App\Listeners;

use App\Events\SquadCreated;
use App\SlotType;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddMobileStorageSlots
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
     * @param SquadCreated $event
     * @return void
     */
    public function handle(SquadCreated $event)
    {
        $squad = $event->squad;
        $slotsNeeded = $squad->mobileStorageRank->getBehavior()->getSlotsCount();
        $currentSlotsCount = $squad->slots()->count();
        $diff = $slotsNeeded - $currentSlotsCount;

        if($diff > 0) {
            $slotType = SlotType::where('name', '=', SlotType::UNIVERSAL)->first();
            for($i = 1; $i <= $diff; $i++) {
                $squad->slots()->create([
                    'slot_type_id' => $slotType->id
                ]);
            }
        }
    }
}
