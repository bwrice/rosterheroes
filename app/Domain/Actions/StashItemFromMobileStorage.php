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
        if (! $this->canStashItem($item, $squad)) {
            throw new \Exception("Item is either not owned or stored locally by Squad");
        }

        $stash = $squad->getLocalStash();
        return $item->attachToHasItems($stash);
    }

    protected function canStashItem(Item $item, Squad $squad)
    {
        if ($item->ownedByMorphable($squad)) {
            return true;
        }

        $localResidence = $squad->getLocalResidence();
        if (! $localResidence) {
            return false;
        }

        return $localResidence->province_id === $squad->province_id;
    }
}
