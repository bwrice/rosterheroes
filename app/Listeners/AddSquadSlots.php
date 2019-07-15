<?php

namespace App\Listeners;

use App\StorableEvents\SquadCreated;
use App\Domain\Models\SlotType;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddSquadSlots
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
     * @param  object  $event
     * @return void
     */
    public function handle(SquadCreated $event)
    {

        $squad = $event->squad;
        $slotsNeededCount = $squad->mobileStorageRank->getBehavior()->getSlotsCount();
        $currentSlotsCount = $squad->slots()->count();
        $diff = $slotsNeededCount - $currentSlotsCount;

        if($diff > 0) {
            /** @var \App\Domain\Models\SlotType $slotType */
            $slotType = SlotType::where('name', '=', SlotType::UNIVERSAL)->first();
            for($i = 1; $i <= $diff; $i++) {
                $squad->slots()->create([
                    'slot_type_id' => $slotType->id
                ]);
            }
        }
    }
}
