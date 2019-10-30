<?php


namespace App\Domain\Interfaces;


use App\Domain\Collections\ItemCollection;
use App\Domain\Models\Item;

interface HasItems extends Morphable, HasUniqueIdentifier
{
    public function getBackupHasItems(): ?HasItems;

    public function hasRoomForItem(Item $item): bool;

    public function itemsToMoveForNewItem(Item $item): ItemCollection;
}
