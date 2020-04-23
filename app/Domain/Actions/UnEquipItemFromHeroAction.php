<?php


namespace App\Domain\Actions;


use App\Domain\Collections\HasItemsCollection;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\Week;
use App\Exceptions\ItemTransactionException;
use App\Facades\CurrentWeek;
use Illuminate\Support\Collection;

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
     * @param HasItemsCollection|null $hasSlots
     * @return HasItemsCollection
     */
    public function execute(Item $item, Hero $hero, HasItemsCollection $hasSlots = null): HasItemsCollection
    {
        if (! $item->doesBelongToMorphable($hero)) {
            throw new ItemTransactionException($item, "Item does not belong to hero", ItemTransactionException::CODE_INVALID_OWNERSHIP);
        }
        if (CurrentWeek::adventuringLocked()) {
            throw new ItemTransactionException($item, "Current week is locked", ItemTransactionException::CODE_TRANSACTION_DISABLED);
        }
        return $this->moveItemToBackupAction->execute($item, $hero, $hasSlots);
    }
}
