<?php


namespace App\Domain\Actions;


use App\Domain\Collections\SlotCollection;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\Slot;
use App\Domain\Models\Squad;
use App\Domain\Support\SlotTransaction;
use App\Exceptions\SlottingException;
use Illuminate\Support\Collection;

class EquipHeroSlotFromWagonAction
{
    public function execute(Hero $hero, Slot $slot, Item $item, Collection $slotTransactions = null)
    {
        $heroSlots = $hero->slots;
        $matchingSlot = $heroSlots->firstMatching($slot);
        if (! $matchingSlot) {
            throw new SlottingException($slot, $hero, null, "Slot does not belong to hero", SlottingException::CODE_INVALID_SLOT_OWNERSHIP);
        }
        $squad = $hero->getSquad();
        if (! $squad) {
            throw new SlottingException($slot, $hero, $item, "No matching squad for hero", SlottingException::CODE_INVALID_ITEM_OWNERSHIP);
        }
        $wagonSlots = $item->slots;
        if (! $wagonSlots->allBelongToHasSlots($squad)) {
            throw new SlottingException($slot, $hero, $item, "Item does not belong to squad", SlottingException::CODE_INVALID_ITEM_OWNERSHIP);
        }
        if (! in_array($slot->slotType->id, $item->getSlotTypeIDs())) {
            throw new SlottingException($slot, $hero, $item, "Invalid slot for hero", SlottingException::CODE_INVALID_SLOT_TYPE);
        }
        $slotTransactions = $slotTransactions ?: collect();
        $slotTransactions->push($this->removeFromWagon($item, $wagonSlots, $squad));
        $slotTransactions->push($this->equipHero($item, $slot, $heroSlots, $hero));
        return $slotTransactions;
    }

    protected function removeFromWagon(Item $item, SlotCollection $wagonSlots, Squad $squad)
    {
        $wagonSlots->emptyItems();
        return new SlotTransaction($wagonSlots, $squad, $item, SlotTransaction::TYPE_EMPTY);
    }

    protected function equipHero(Item $item, Slot $slotToFill, SlotCollection $heroSlots, Hero $hero)
    {
        $item->slots()->save($slotToFill);
        return new SlotTransaction(new SlotCollection([$slotToFill]), $hero, $item, SlotTransaction::TYPE_FILL);
    }
}
