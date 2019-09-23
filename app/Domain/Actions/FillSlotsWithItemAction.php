<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/16/18
 * Time: 5:15 PM
 */

namespace App\Domain\Actions;


use App\Domain\Collections\ItemCollection;
use App\Domain\Interfaces\HasSlots;
use App\Domain\Interfaces\Slottable;
use App\Domain\Models\Item;
use App\Exceptions\FillSlotException;

class FillSlotsWithItemAction
{

    /**
     * @param HasSlots $hasSlots
     * @param Item $item
     * @param bool $secondAttempt
     * @param ItemCollection|null $transaction
     * @return ItemCollection
     * @throws FillSlotException
     */
    public function execute(HasSlots $hasSlots, Item $item, $secondAttempt = false, ItemCollection $transaction = null): ItemCollection
    {
        $transaction = $transaction ?: new ItemCollection();
        $item->getSlots()->emptySlottables();
        $slotTypeIDs = $item->getSlotTypeIDs();
        $slotsNeededCount = $item->getSlotsCount();
        $emptySlots = $hasSlots->getEmptySlots($slotsNeededCount, $slotTypeIDs);

        $slotsToEmptyCount = $slotsNeededCount - $emptySlots->count();

        if ($slotsToEmptyCount > 0) {

            if ($secondAttempt) {
                $message = "Failed to equip";
                throw new FillSlotException($hasSlots, $item, $message, FillSlotException::CODE_SECOND_ATTEMPT);
            }

            $backup = $hasSlots->getBackupHasSlots();

            if (! $backup) {
                $message = "Failed to find back-up storage";
                throw new FillSlotException($hasSlots, $item, $message, FillSlotException::NO_BACKUP);
            }

            // Get slots we need to remove from their slots to make space for what we intend to slot
            $items = $hasSlots->getSlots()->slotFilled()->take($slotsToEmptyCount)->getItems();
            // Now get the slots those slottables are currently slotted into and empty them
            $items->getSlots()->emptySlottables();

            // Re-slot the slottables that were removed
            $items->each(function (Item $item) use ($backup, $transaction) {
                $transaction->merge($this->execute($backup, $item, false, $transaction));
            });

            //Now try to slot the original slottable again
            $transaction->merge($this->execute($hasSlots->getFresh(), $item, true, $transaction));
        } else {
            $slots = $emptySlots->take($slotsNeededCount);
            $item->slots()->saveMany($slots);
            $transaction->push($item->fresh());
        }
        return $transaction;
    }
}
