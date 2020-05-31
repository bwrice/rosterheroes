<?php


namespace App\Domain\Collections;


use App\Domain\Behaviors\ItemBases\ItemBaseBehavior;
use App\Domain\Interfaces\FillsGearSlots;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\Support\GearSlots\GearSlot;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class GearSlotCollection extends Collection
{
    public function setItems(ItemCollection $items)
    {
        $items->each(function (Item $item) {
            $availableGearSlots = $this->slotEmpty()->byPriority()->forGearSlotTypes($item->getValidGearSlotTypes());
            $slotsNeededCount = $item->getGearSlotsNeededCount();
            if ($availableGearSlots->count() < $slotsNeededCount) {
                Log::warning("Available gear slots less than needed when filling for: " . $item->getUuid());
            }
            $availableGearSlots->take($slotsNeededCount)->each(function (GearSlot $gearSlot) use ($item) {
                $gearSlot->setItem($item);
            });
        });
        return $this;
    }

    public function slotEmpty()
    {
        return $this->filter(function (GearSlot $gearSlot) {
            return is_null($gearSlot->getItem());
        });
    }

    public function forGearSlotTypes(array $gearSlotTypes)
    {
        return $this->filter(function (GearSlot $gearSlot) use ($gearSlotTypes) {
            return in_array($gearSlot->getType(), $gearSlotTypes);
        });
    }

    public function sortedByUnEquipPriority()
    {
        return $this->sortByDesc(function (GearSlot $gearSlot) {
            $emptyPriority = is_null($gearSlot->getItem()) ? 99 : 0;
            return $gearSlot->getPriority() + $emptyPriority;
        });
    }

    public function byPriority()
    {
        return $this->sortBy(function (GearSlot $gearSlot) {
            return $gearSlot->getPriority();
        });
    }

    public function withItem()
    {
        return $this->filter(function (GearSlot $gearSlot) {
            return $gearSlot->getItem() instanceof Item;
        });
    }

    public function withItemOrEmpty()
    {
        return $this->filter(function (GearSlot $gearSlot) {
            $filler = $gearSlot->getItem();
            return is_null($filler) || $filler instanceof Item;
        });
    }

    public function itemsToUnEquipToEquipNewItem(ItemBaseBehavior $itemBaseBehavior): ItemCollection
    {
        $validSlotTypes = $itemBaseBehavior->getValidGearSlotTypes();
        $slotsNeededCount = $itemBaseBehavior->getGearSlotsCount();

        $filtered = $this->forGearSlotTypes($validSlotTypes)
            ->sortedByUnEquipPriority()
            ->withItemOrEmpty()
            ->take($slotsNeededCount);

        if ($filtered->count() < $slotsNeededCount) {
            throw new \RuntimeException("Not enough valid gear slots for item");
        }
        return $filtered->mapIntoItems();
    }

    public function mapIntoItems(): ItemCollection
    {
        $items = new ItemCollection();
        $this->withItem()->each(function (GearSlot $gearSlot) use ($items) {
            $items->push($gearSlot->getItem());
        });
        return $items->unique();
    }
}
