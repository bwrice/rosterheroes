<?php


namespace App\Domain\Actions;


use App\Domain\Models\Hero;
use App\Domain\Models\Slot;
use App\Domain\Support\SlotTransaction;
use App\Exceptions\SlottingException;
use Illuminate\Support\Collection;

class EmptyHeroSlotAction
{
    /**
     * @var SlotItemInSquadAction
     */
    private $slotItemInSquadAction;

    public function __construct(SlotItemInSquadAction $slotItemInSquadAction)
    {
        $this->slotItemInSquadAction = $slotItemInSquadAction;
    }

    /**
     * @param Slot $slot
     * @param Hero $hero
     * @param Collection|null $slotTransactions
     * @return Collection
     */
    public function execute(Slot $slot, Hero $hero, Collection $slotTransactions = null)
    {
        $slotTransactions = $slotTransactions ?: collect();
        if ($slot->hasSlots->getUniqueIdentifier() != $hero->getUniqueIdentifier()) {
            throw new SlottingException($slot, $hero, null,"Slot does not belong to Hero", SlottingException::CODE_INVALID_SLOT_OWNERSHIP);
        }

        $item = $slot->item;
        if (! $item) {
            throw new SlottingException($slot, $hero, null,"Slot is already empty", SlottingException::CODE_ALREADY_EMPTY);
        }

        $squad = $hero->getSquad();
        if (! $squad) {
            throw new SlottingException($slot, $hero, null,"No squad available for hero", SlottingException::CODE_NO_BACKUP);
        }

        $filledSlots = $hero->slots->filledWithItem($item);
        $filledSlots->emptyItems();

        $transaction = new SlotTransaction($filledSlots, $hero, $item, SlotTransaction::TYPE_EMPTY);
        $slotTransactions->push($transaction);

        $slotTransactions = $this->slotItemInSquadAction->execute($squad, $item, $slotTransactions);

        return $slotTransactions;
    }
}
