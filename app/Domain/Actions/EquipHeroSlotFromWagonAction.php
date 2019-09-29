<?php


namespace App\Domain\Actions;


use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\Slot;
use App\Exceptions\SlottingException;

class EquipHeroSlotFromWagonAction
{
    public function execute(Hero $hero, Slot $slot, Item $item)
    {
        $heroSlots = $hero->slots;
        $matchingSlot = $heroSlots->first(function (Slot $heroSlot) use ($slot) {
            return (string) $heroSlot->uuid === (string) $slot->uuid;
        });
        if (! $matchingSlot) {
            throw new SlottingException($slot, $hero, null, "Slot does not belong to hero", SlottingException::CODE_INVALID_SLOT_OWNERSHIP);
        }
        $squad = $hero->getSquad();
        if (! $squad) {
            throw new SlottingException($slot, $hero, $item, "No matching squad for hero", SlottingException::CODE_INVALID_ITEM_OWNERSHIP);
        }
        if (! $squad->hasItem($item)) {
            throw new SlottingException($slot, $hero, $item, "Item does not belong to squad", SlottingException::CODE_INVALID_ITEM_OWNERSHIP);
        }
        if (! in_array($slot->slotType->id, $item->getSlotTypeIDs())) {
            throw new SlottingException($slot, $hero, $item, "Invalid slot for hero", SlottingException::CODE_INVALID_SLOT_TYPE);
        }
    }
}
