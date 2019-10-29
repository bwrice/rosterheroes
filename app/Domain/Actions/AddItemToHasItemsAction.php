<?php


namespace App\Domain\Actions;


use App\Domain\Interfaces\HasItems;
use App\Domain\Models\Item;
use Illuminate\Support\Collection;

class AddItemToHasItemsAction
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

    public function execute(Item $item, HasItems $hasItems, Collection $hasItemsCollection = null): Collection
    {
        $hasItemsCollection = $hasItemsCollection ?: collect();

        if ($item->hasItems) {
            // Add original owner to collection then remove relationship
            $hasItemsCollection->push($item->hasItems);
            $item->item_type_id = null;
            $item->has_items_id = null;
            $item->save();
        }

        if (! $hasItems->hasRoomForItem($item)) {
            // Make room for new item
            $items = $hasItems->itemsToMoveForNewItem($item);
            $backup = $hasItems->getBackupHasItems();
            $items->each(function (Item $item) use ($backup, &$hasItemsCollection) {
                $hasItemsCollection = $this->moveItemToBackupAction->execute($item, $backup, $hasItemsCollection);
            });
        }

        // Attach new item
        $item->has_items_id = $hasItems->getMorphID();
        $item->has_items_type = $hasItems->getMorphType();
        $item->save();
        $hasItemsCollection->push($hasItems);
        return $hasItemsCollection;
    }
}
