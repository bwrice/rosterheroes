<?php


namespace App\Domain\Interfaces;


use App\Domain\Collections\ItemCollection;
use App\Domain\Models\Item;

interface HasItems
{
    public function getBackupHasItems(): ?HasItems;

    public function hasRoomForItem(Item $item): bool;

    public function itemsToMoveForNewItem(Item $item): ItemCollection;

    public function getMorphType(): string;

    public function getMorphID(): int;
}
