<?php


namespace App\Domain\Actions;


use App\Domain\Collections\HasItemsCollection;
use App\Domain\Interfaces\HasItems;
use App\Domain\Models\Item;
use App\Exceptions\ItemTransactionException;

class MoveItemToBackupAction
{
    /**
     * @param Item $item
     * @param HasItems $hasItems
     * @param HasItemsCollection|null $hasItemsCollection
     * @param bool $originalItemSource
     * @return HasItemsCollection
     */
    public function execute(Item $item, HasItems $hasItems, HasItemsCollection $hasItemsCollection = null, $originalItemSource = true): HasItemsCollection
    {
        $hasItemsCollection = $hasItemsCollection ?: new HasItemsCollection();

        if (! $hasItems->getBackupHasItems()) {
            throw new ItemTransactionException($item,"No backup for item found", ItemTransactionException::CODE_NO_BACKUP);
        }
        if ($originalItemSource) {
            $hasItemsCollection->push($hasItems);
        }
        $backup = $hasItems->getBackupHasItems();
        if ($backup->hasRoomForItem($item)) {
            $item->attachToHasItems($backup);
            $hasItemsCollection->push($backup);
            return $hasItemsCollection;
        } else {
            // Call execute recursively to try to attach to the backup's backup
            return $this->execute($item, $backup, $hasItemsCollection, false);
        }
    }
}
