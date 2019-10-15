<?php


namespace App\Domain\Actions;


use App\Domain\Collections\SlotTransactionCollection;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\Slot;
use App\Domain\Models\Squad;
use App\Domain\Support\SlotTransaction;
use App\Domain\Support\SlotTransactionGroup;
use App\Exceptions\SlottingException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EmptyHeroSlotAction
{

    /** @var Slot */
    protected $slotToEmpty;

    /** @var Hero */
    protected $hero;

    /** @var Item */
    protected $item;

    /** @var Squad */
    protected $squad;

    /** @var SlotTransactionGroup */
    protected $slotTransactionGroup;

    /** @var SlotItemInWagonAction */
    protected $slotItemInWagonAction;

    public function __construct(SlotItemInWagonAction $slotItemInSquadAction)
    {
        $this->slotItemInWagonAction = $slotItemInSquadAction;
    }

    /**
     * @param Slot $slot
     * @param Hero $hero
     * @param SlotTransactionGroup|null $slotTransactionGroup
     * @return SlotTransactionGroup
     */
    public function execute(Slot $slot, Hero $hero, SlotTransactionGroup $slotTransactionGroup = null)
    {
        $this->setProps($slot, $hero, $slotTransactionGroup);
        $this->validate();

        DB::transaction(function () {
            $this->unEquipItem();
            $this->slotTransactionGroup = $this->slotItemInWagonAction->execute($this->squad, $this->item, $this->slotTransactionGroup);
        });

        return $this->slotTransactionGroup;
    }

    /**
     * @param Slot $slot
     * @param Hero $hero
     * @param SlotTransactionGroup|null $slotTransactionGroup
     */
    protected function setProps(Slot $slot, Hero $hero, SlotTransactionGroup $slotTransactionGroup = null)
    {
        $this->slotToEmpty = $slot;
        $this->hero = $hero;
        $this->slotTransactionGroup = $slotTransactionGroup ?: new SlotTransactionGroup();
    }

    protected function validate()
    {
        if (! $this->slotToEmpty->belongsToHasSlots($this->hero)) {
            throw new SlottingException($this->slotToEmpty, $this->hero, null,"Slot does not belong to Hero", SlottingException::CODE_INVALID_SLOT_OWNERSHIP);
        }

        $this->item = $this->slotToEmpty->item;
        if (! $this->item) {
            throw new SlottingException($this->slotToEmpty, $this->hero, null,"Slot is already empty", SlottingException::CODE_ALREADY_EMPTY);
        }

        $this->squad = $this->hero->getSquad();
        if (! $this->squad) {
            throw new SlottingException($this->slotToEmpty, $this->hero, null,"No squad available for hero", SlottingException::CODE_NO_BACKUP);
        }
    }

    protected function unEquipItem()
    {
        $filledSlots = $this->item->slots;
        $filledSlots->emptyItems();

        $this->slotTransactionGroup->setHero($this->hero);
    }
}
