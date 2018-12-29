<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/16/18
 * Time: 5:15 PM
 */

namespace App\Slots;


class Slotter
{
    public function slot(HasSlots $hasSlots, Slottable $slottable, $secondAttempt = false)
    {
        $slotTypeIDs = $slottable->getSlotTypeIDs();
        $slotsNeededCount = $slottable->getSlotsCount();
        $emptySlots = $hasSlots->getEmptySlots($slotsNeededCount, $slotTypeIDs);

        $slotsToEmptyCount = $slotsNeededCount - $emptySlots->count();

        if ($slotsToEmptyCount > 0) {

            if ($secondAttempt) {
                throw new \RuntimeException("Not enough empty slots on 2nd attempt of equipping");
            }

            $backup = $hasSlots->getBackupHasSlots();

            if (! $backup) {
                throw new \RuntimeException("Not enough empty slots with no back-up available");
            }

            // Get slots we need to remove from their slots to make space for what we intend to slot
            $slottables = $hasSlots->getSlots()->filled()->take($slotsToEmptyCount)->getSlottables();
            // Now get the slots those slottables are currently slotted into and empty them
            $slottables->getSlots()->emptySlottables();
            // Re-slot the slottables that were removed
            $slottables->each(function (Slottable $slottable) use ($backup) {
                $this->slot($backup, $slottable, false);
            });

            //Now try to slot the original slottable again
            $this->slot( $hasSlots->getFresh(), $slottable, true );

        } else {
            $slots = $emptySlots->take($slotsNeededCount);
            $slottable->slots()->saveMany($slots);
        }
    }

}