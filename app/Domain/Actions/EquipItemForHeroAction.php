<?php


namespace App\Domain\Actions;


use App\Domain\Collections\ItemCollection;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Exceptions\ItemTransactionException;
use App\Facades\CurrentWeek;
use Illuminate\Support\Facades\DB;

class EquipItemForHeroAction
{
    /**
     * @var AddItemToHasItems
     */
    protected $addItemToHasItemsAction;

    public function __construct(AddItemToHasItems $addItemToHasItemsAction)
    {
        $this->addItemToHasItemsAction = $addItemToHasItemsAction;
    }

    public function execute(Item $item, Hero $hero, ItemCollection $itemsMoved = null): ItemCollection
    {
        $itemsMoved = $itemsMoved ?: new ItemCollection();
        $squad = $hero->squad;

        if(! $item->ownedByMorphable($squad)) {
            throw new ItemTransactionException($item, "Item does not belong to wagon", ItemTransactionException::CODE_INVALID_OWNERSHIP);
        }

        if(CurrentWeek::adventuringLocked()) {
            throw new ItemTransactionException($item, "Week is currently locked", ItemTransactionException::CODE_TRANSACTION_DISABLED);
        }

        return DB::transaction(function () use ($item, $hero, $itemsMoved) {
            return $this->addItemToHasItemsAction->execute($item, $hero, $itemsMoved);
        });
    }
}
