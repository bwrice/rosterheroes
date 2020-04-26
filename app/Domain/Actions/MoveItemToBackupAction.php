<?php


namespace App\Domain\Actions;

use App\Domain\Collections\ItemCollection;
use App\Domain\Interfaces\HasItems;
use App\Domain\Models\Item;
use App\Exceptions\ItemTransactionException;

class MoveItemToBackupAction
{
    /**
     * @param Item $item
     * @param HasItems $hasItems
     * @param ItemCollection|null $itemsMoved
     * @return ItemCollection
     */
    public function execute(Item $item, HasItems $hasItems, ItemCollection $itemsMoved = null): ItemCollection
    {
        $itemsMoved = $itemsMoved ?: new ItemCollection();

        if (! $hasItems->getBackupHasItems()) {
            throw new ItemTransactionException($item,"No backup for item found", ItemTransactionException::CODE_NO_BACKUP);
        }

        $backup = $hasItems->getBackupHasItems();
        if ($backup->hasRoomForItem($item)) {
            $item->attachToMorphable($backup);
            return $itemsMoved->push($item);
        } else {
            // Call execute recursively to try to attach to the backup's backup
            return $this->execute($item, $backup, $itemsMoved);
        }
    }
}
