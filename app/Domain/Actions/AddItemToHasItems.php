<?php


namespace App\Domain\Actions;


use App\Domain\Collections\HasItemsCollection;
use App\Domain\Interfaces\HasItems;
use App\Domain\Models\Item;

class AddItemToHasItems
{
    /**
     * @var MoveItemToBackupAction
     */
    private $moveItemToBackupAction;

    /**
     * AddItemToHasItemsAction constructor.
     * @param MoveItemToBackupAction $moveItemToBackupAction
     */
    public function __construct(MoveItemToBackupAction $moveItemToBackupAction)
    {
        $this->moveItemToBackupAction = $moveItemToBackupAction;
    }

    public function execute(Item $item, HasItems $hasItems, HasItemsCollection $hasItemsCollection = null): HasItemsCollection
    {
        $hasItemsCollection = $hasItemsCollection ?: new HasItemsCollection();

        if ($item->hasItems) {
            // Add original owner to collection then remove relationship
            $hasItemsCollection->push($item->hasItems);
            $item = $item->clearHasItems();
        }

        if (! $hasItems->hasRoomForItem($item)) {
            // Make room for new item
            $items = $hasItems->itemsToMoveForNewItem($item);
            $items->each(function (Item $item) use ($hasItems, &$hasItemsCollection) {
                $hasItemsCollection = $this->moveItemToBackupAction->execute($item, $hasItems, $hasItemsCollection);
            });
        }

        // Attach new item
        $item->attachToHasItems($hasItems);
        $hasItemsCollection->push($hasItems);
        return $hasItemsCollection;
    }
}