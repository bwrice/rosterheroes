<?php


namespace App\Domain\Actions;


use App\Domain\Collections\ItemCollection;
use App\Domain\Interfaces\HasItems;
use App\Domain\Models\Item;

class AddItemToHasItems
{
    /**
     * @var MoveItemToBackupAction
     */
    protected $moveItemToBackupAction;

    /**
     * AddItemToHasItemsAction constructor.
     * @param MoveItemToBackupAction $moveItemToBackupAction
     */
    public function __construct(MoveItemToBackupAction $moveItemToBackupAction)
    {
        $this->moveItemToBackupAction = $moveItemToBackupAction;
    }

    /**
     * @param Item $item
     * @param HasItems $hasItems
     * @param ItemCollection|null $itemsMoved
     * @param bool $prioritize
     * @return ItemCollection
     */
    public function execute(Item $item, HasItems $hasItems, ItemCollection $itemsMoved = null, $prioritize = true): ItemCollection
    {
        $itemsMoved = $itemsMoved ?: new ItemCollection();

        if ($hasItems->hasRoomForItem($item)) {
            return $this->attachNewItem($item, $hasItems, $itemsMoved);
        }

        if ($prioritize) {
            // Make room for new item
            $items = $hasItems->itemsToMoveForNewItem($item);
            $items->each(function (Item $item) use ($hasItems, &$itemsMoved) {
                $itemsMoved = $this->moveItemToBackupAction->execute($item, $hasItems, $itemsMoved);
            });
            return $this->attachNewItem($item, $hasItems, $itemsMoved);
        }

        return $this->moveItemToBackupAction->execute($item, $hasItems, $itemsMoved);
    }

    protected function attachNewItem(Item $item, HasItems $hasItems, ItemCollection $itemsMoved)
    {
        $item->attachToHasItems($hasItems);
        return $itemsMoved->push($item);
    }
}
