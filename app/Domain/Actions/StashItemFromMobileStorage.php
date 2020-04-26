<?php


namespace App\Domain\Actions;


use App\Domain\Models\Item;
use App\Domain\Models\Squad;

class StashItemFromMobileStorage
{
    /**
     * @param Item $item
     * @param Squad $squad
     * @return Item
     * @throws \Exception
     */
    public function execute(Item $item, Squad $squad)
    {
        if (! $item->ownedByMorphable($squad)) {
            throw new \Exception("Item is not in the squad's mobile storage");
        }

        $stash = $squad->getLocalStash();
        return $item->attachToHasItems($stash);
    }
}
