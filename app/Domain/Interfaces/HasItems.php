<?php


namespace App\Domain\Interfaces;


use App\Domain\Collections\ItemCollection;
use App\Domain\Models\Item;
use Illuminate\Http\Resources\Json\JsonResource;

interface HasItems extends Morphable
{
    public function getBackupHasItems(): ?HasItems;

    public function hasRoomForItem(Item $item): bool;

    public function itemsToMoveForNewItem(Item $item): ItemCollection;

    public function getTransactionIdentification(): array;
}
