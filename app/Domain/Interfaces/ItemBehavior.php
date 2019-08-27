<?php


namespace App\Domain\Interfaces;


interface ItemBehavior
{
    public function getSlotsCount(): int;

    public function getItemGroup(): string;

    public function getSlotTypeIDs(): array;
}
