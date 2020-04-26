<?php


namespace App\Domain\Actions;


use App\Domain\Collections\ItemCollection;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Exceptions\ItemTransactionException;
use App\Facades\CurrentWeek;

class UnEquipItemFromHeroAction
{
    /**
     * @var MoveItemToBackupAction
     */
    protected $moveItemToBackupAction;

    public function __construct(MoveItemToBackupAction $moveItemToBackupAction)
    {
        $this->moveItemToBackupAction = $moveItemToBackupAction;
    }

    /**
     * @param Item $item
     * @param Hero $hero
     * @param ItemCollection|null $itemsMoved
     * @return ItemCollection
     */
    public function execute(Item $item, Hero $hero, ItemCollection $itemsMoved = null): ItemCollection
    {
        if (! $item->ownedByMorphable($hero)) {
            throw new ItemTransactionException($item, "Item does not belong to hero", ItemTransactionException::CODE_INVALID_OWNERSHIP);
        }
        if (CurrentWeek::adventuringLocked()) {
            throw new ItemTransactionException($item, "Current week is locked", ItemTransactionException::CODE_TRANSACTION_DISABLED);
        }
        return $this->moveItemToBackupAction->execute($item, $hero, $itemsMoved);
    }
}
