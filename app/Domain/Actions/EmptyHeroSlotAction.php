<?php


namespace App\Domain\Actions;


use App\Domain\Collections\SlotTransactionCollection;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\Slot;
use App\Domain\Models\Squad;
use App\Domain\Support\SlotTransaction;
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

    /** @var SlotTransactionCollection */
    protected $slotTransactions;

    /** @var SlotItemInWagonAction */
    protected $slotItemInWagonAction;

    public function __construct(SlotItemInWagonAction $slotItemInSquadAction)
    {
        $this->slotItemInWagonAction = $slotItemInSquadAction;
    }

    /**
     * @param Slot $slot
     * @param Hero $hero
     * @param SlotTransactionCollection|null $slotTransactions
     * @return Collection
     */
    public function execute(Slot $slot, Hero $hero, SlotTransactionCollection $slotTransactions = null)
    {
        $this->setProps($slot, $hero, $slotTransactions);
        $this->validate();

        DB::transaction(function () {
            $this->unEquipItem();
            $this->slotTransactions = $this->slotItemInWagonAction->execute($this->squad, $this->item, $this->slotTransactions);
        });

        return $this->slotTransactions;
    }

    /**
     * @param Slot $slot
     * @param Hero $hero
     * @param SlotTransactionCollection|null $slotTransactions
     */
    protected function setProps(Slot $slot, Hero $hero, SlotTransactionCollection $slotTransactions = null)
    {
        $this->slotToEmpty = $slot;
        $this->hero = $hero;
        $this->slotTransactions = $slotTransactions ?: new SlotTransactionCollection();
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

        $transaction = new SlotTransaction($filledSlots, $this->hero, $this->item, SlotTransaction::TYPE_EMPTY);
        $this->slotTransactions->push($transaction);
    }
}
