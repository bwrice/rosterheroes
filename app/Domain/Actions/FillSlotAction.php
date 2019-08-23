<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/16/18
 * Time: 5:15 PM
 */

namespace App\Domain\Actions;


use App\Domain\Interfaces\HasSlots;
use App\Domain\Interfaces\Slottable;
use App\Exceptions\FillSlotException;

class FillSlotAction
{

    /**
     * @param HasSlots $hasSlots
     * @param Slottable $slottable
     * @param bool $secondAttempt
     * @throws FillSlotException
     */
    public function execute(HasSlots $hasSlots, Slottable $slottable, $secondAttempt = false)
    {
        $slotTypeIDs = $slottable->getSlotTypeIDs();
        $slotsNeededCount = $slottable->getSlotsCount();
        $emptySlots = $hasSlots->getEmptySlots($slotsNeededCount, $slotTypeIDs);

        $slotsToEmptyCount = $slotsNeededCount - $emptySlots->count();

        if ($slotsToEmptyCount > 0) {

            if ($secondAttempt) {
                $message = "Failed to equip";
                throw new FillSlotException($hasSlots, $slottable, $message, FillSlotException::CODE_SECOND_ATTEMPT);
            }

            $backup = $hasSlots->getBackupHasSlots();

            if (! $backup) {
                $message = "Failed to find back-up storage";
                throw new FillSlotException($hasSlots, $slottable, $message, FillSlotException::NO_BACKUP);
            }

            // Get slots we need to remove from their slots to make space for what we intend to slot
            $slottables = $hasSlots->getSlots()->filled()->take($slotsToEmptyCount)->getSlottables();
            // Now get the slots those slottables are currently slotted into and empty them
            $slottables->getSlots()->emptySlottables();

            // Re-slot the slottables that were removed
            $slottables->each(function (Slottable $slottable) use ($backup) {
                $this->execute($backup, $slottable, false);
            });

            //Now try to slot the original slottable again
            $this->execute($hasSlots->getFresh(), $slottable, true);
        } else {
            $slots = $emptySlots->take($slotsNeededCount);
            $slottable->slots()->saveMany($slots);
        }
    }
}
