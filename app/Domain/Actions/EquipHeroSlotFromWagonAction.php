<?php


namespace App\Domain\Actions;


use App\Domain\Collections\SlotCollection;
use App\Domain\Collections\SlotTransactionCollection;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\Slot;
use App\Domain\Models\Squad;
use App\Domain\Models\Week;
use App\Domain\Support\SlotTransaction;
use App\Domain\Support\ItemTransactionGroup;
use App\Exceptions\SlottingException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EquipHeroSlotFromWagonAction
{

    /** @var Hero */
    protected $hero;

    /** @var SlotCollection */
    protected $heroSlots;

    /** @var Slot */
    protected $slotToFill;

    /** @var Item */
    protected $itemToEquip;

    /** @var ItemTransactionGroup */
    protected $slotTransactionGroup;

    /** @var Squad */
    protected $squad;

    /** @var SlotCollection */
    protected $filledWagonSlots;

    /**
     * @var EmptyHeroSlotAction
     */
    protected $emptyHeroSlotAction;

    public function __construct(EmptyHeroSlotAction $emptyHeroSlotAction)
    {
        $this->emptyHeroSlotAction = $emptyHeroSlotAction;
    }

    public function execute(Hero $hero, Slot $slot, Item $item, ItemTransactionGroup $slotTransactionGroup = null)
    {
        if (! Week::current()->adventuringOpen()) {
            throw new SlottingException($slot, $hero, $item, "Week is currently locked for that action");
        }

        $this->setProps($hero, $slot, $item, $slotTransactionGroup);
        $this->validate();
        DB::transaction(function () {
            $this->removeFromWagon();
            $this->equipHero();
        });
        return $this->slotTransactionGroup;
    }

    protected function setProps(Hero $hero, Slot $slot, Item $item, ItemTransactionGroup $slotTransactionGroup = null)
    {
        $this->hero = $hero;
        $this->slotToFill = $slot;
        $this->itemToEquip = $item;
        $this->filledWagonSlots = $item->slots;
        $this->heroSlots = $hero->slots;
        $this->slotTransactionGroup = $slotTransactionGroup ?: new ItemTransactionGroup();
    }

    protected function validate()
    {
        $matchingSlot = $this->heroSlots->firstMatching($this->slotToFill);
        if (! $matchingSlot) {
            throw new SlottingException($this->slotToFill, $this->hero, null, "Slot does not belong to hero", SlottingException::CODE_INVALID_SLOT_OWNERSHIP);
        }
        $this->squad = $this->hero->getSquad();
        if (! $this->squad) {
            throw new SlottingException($this->slotToFill, $this->hero, $this->itemToEquip, "No matching squad for hero", SlottingException::CODE_INVALID_ITEM_OWNERSHIP);
        }
        if (! $this->filledWagonSlots->allBelongToHasSlots($this->squad)) {
            throw new SlottingException($this->slotToFill, $this->hero, $this->itemToEquip, "Item does not belong to squad", SlottingException::CODE_INVALID_ITEM_OWNERSHIP);
        }
        if (! in_array($this->slotToFill->slotType->id, $this->itemToEquip->getSlotTypeIDs())) {
            throw new SlottingException($this->slotToFill, $this->hero, $this->itemToEquip, "Invalid slot for hero", SlottingException::CODE_INVALID_SLOT_TYPE);
        }
    }

    protected function removeFromWagon()
    {
        $this->filledWagonSlots->emptyItems();
        $this->slotTransactionGroup->setSquad($this->squad);
    }

    protected function equipHero()
    {
        $extraSlotsNeeded = $this->itemToEquip->getSlotsCount() - 1;

        if ($extraSlotsNeeded > 0) {
            $validSlotTypeIDs = $this->itemToEquip->getSlotTypeIDs();
            /*
             * Take the number of valid slots needed not including the slot we
             * already intend to fill
             */
            $heroSlotsToFill = $this->heroSlots->reject(function (Slot $slot) {
                return $slot->id === $this->slotToFill->id;
            })->withSlotTypes($validSlotTypeIDs)
                ->take($extraSlotsNeeded);

        } else {
            $heroSlotsToFill = new SlotCollection();
        }

        $heroSlotsToFill = $heroSlotsToFill->push($this->slotToFill);
        $heroSlotsToEmpty = $heroSlotsToFill->slotFilled()->uniqueByItem();
        $heroSlotsToEmpty->each(function (Slot $slot) {
            $this->slotTransactionGroup = $this->emptyHeroSlotAction->execute($slot, $this->hero, $this->slotTransactionGroup);
        });

        $heroSlotsToFill = $heroSlotsToFill->fresh();

        $this->itemToEquip->slots()->saveMany($heroSlotsToFill);
        $this->slotTransactionGroup->setHero($this->hero);
    }
}
