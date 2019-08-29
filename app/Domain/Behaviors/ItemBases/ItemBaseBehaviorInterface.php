<?php


namespace App\Domain\Behaviors\ItemBases;


interface ItemBaseBehaviorInterface
{
    public function getSlotsCount(): int;

    public function getGroupName(): string;

    public function getSlotTypeIDs(): array;
}
