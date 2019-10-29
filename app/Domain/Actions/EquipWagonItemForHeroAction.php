<?php


namespace App\Domain\Actions;


use App\Domain\Collections\HasItemsCollection;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\Week;
use App\Exceptions\ItemTransactionException;

class EquipWagonItemForHeroAction
{
    public function execute(Item $item, Hero $hero, HasItemsCollection $hasItemsCollection = null)
    {
        $hasItemsCollection = $hasItemsCollection ?: new HasItemsCollection();
        $squad = $hero->getSquad();

        if(! $item->belongsToHasItems($squad)) {
            throw new ItemTransactionException($item, "Item does not belong to wagon", ItemTransactionException::CODE_INVALID_OWNERSHIP);
        }

        if(! Week::current()->adventuringOpen()) {
            throw new ItemTransactionException($item, "Week is currently locked", ItemTransactionException::CODE_TRANSACTION_DISABLED);
        }

        $item = $item->clearHasItems();
    }
}
