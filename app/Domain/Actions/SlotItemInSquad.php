<?php


namespace App\Domain\Actions;


use App\Domain\Models\Item;
use App\Domain\Models\Squad;

class SlotItemInSquad
{
    public function execute(Squad $squad, Item $item)
    {
        $slotsNeeded = $item->getSlotsCount();
        $slotsToFill = $squad->slots()->take($slotsNeeded)->get();
        $item->slots()->saveMany($slotsToFill);
    }
}
