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

    /** @var Collection */
    protected $slotTransactions;

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

    public function execute(Hero $hero, Slot $slot, Item $item, Collection $slotTransactions = null)
    {
        $this->setProps($hero, $slot, $item, $slotTransactions);
        $this->validate();
        DB::transaction(function () {
            $this->removeFromWagon();
            $this->equipHero();
        });
        return $this->slotTransactions;
    }

    protected function setProps(Hero $hero, Slot $slot, Item $item, Collection $slotTransactions = null)
    {
        $this->hero = $hero;
        $this->slotToFill = $slot;
        $this->itemToEquip = $item;
        $this->filledWagonSlots = $item->slots;
        $this->heroSlots = $hero->slots;
        $this->slotTransactions = $slotTransactions ?: collect();
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
        $transaction = new SlotTransaction($this->filledWagonSlots, $this->squad, $this->itemToEquip->fresh(), SlotTransaction::TYPE_EMPTY);
        $this->slotTransactions->push($transaction);
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
            $this->slotTransactions = $this->slotTransactions->merge($this->emptyHeroSlotAction->execute($slot, $this->hero));
        });

        $heroSlotsToFill = $heroSlotsToFill->fresh();

        $this->itemToEquip->slots()->saveMany($heroSlotsToFill);

        $transaction = new SlotTransaction($heroSlotsToFill, $this->hero, $this->itemToEquip->fresh(), SlotTransaction::TYPE_FILL);
        $this->slotTransactions->push($transaction);
    }
}
