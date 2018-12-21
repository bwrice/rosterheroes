<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/16/18
 * Time: 5:15 PM
 */

namespace App\Slots;


class Equipper
{
    public function equip(HasSlots $hasSlots, Slottable $slottable, $secondAttempt = false)
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

            $unequippedSlottables = $hasSlots->emptySlots($slotsToEmptyCount, $slotTypeIDs);
            $unequippedSlottables->each(function (Slottable $slottable) use ($backup) {
                $this->equip($backup, $slottable, false);
            });

            $this->equip( $hasSlots->getFresh(), $slottable, true );

        } else {
            $slots = $emptySlots->take($slotsNeededCount);
            $slottable->slots()->saveMany($slots);
        }
    }

}