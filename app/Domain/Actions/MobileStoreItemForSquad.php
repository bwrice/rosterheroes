<?php


namespace App\Domain\Actions;


use App\Domain\Models\Item;
use App\Domain\Models\Squad;

class MobileStoreItemForSquad
{
    /**
     * @var AddItemToHasItems
     */
    protected $addItemToHasItems;

    public function __construct(AddItemToHasItems $addItemToHasItems)
    {
        $this->addItemToHasItems = $addItemToHasItems;
    }

    public function execute(Item $item, Squad $squad)
    {
        if (! $this->canMobileStoreItem($item, $squad)) {
            throw new \Exception("Item not in local storage of squad");
        }

        return $this->addItemToHasItems->execute($item, $squad);
    }

    protected function canMobileStoreItem(Item $item, Squad $squad)
    {
        $localStash = $squad->getLocalStash();
        if ($item->ownedByMorphable($localStash)) {
            return $localStash->province_id === $squad->province_id;
        }

        $localResidence = $squad->getLocalResidence();
        if ($localResidence && $item->ownedByMorphable($localResidence)) {
            return $localResidence->province_id === $squad->province_id;
        }
        return false;
    }
}
