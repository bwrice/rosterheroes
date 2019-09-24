<?php


namespace App\Domain\Actions;


use App\Domain\Models\Hero;
use App\Domain\Models\Slot;
use App\Exceptions\SlottingException;

class EmptyHeroSlot
{
    /**
     * @var SlotItemInSquad
     */
    private $slotItemInSquadAction;

    public function __construct(SlotItemInSquad $slotItemInSquadAction)
    {
        $this->slotItemInSquadAction = $slotItemInSquadAction;
    }

    /**
     * @param Slot $slot
     * @param Hero $hero
     */
    public function execute(Slot $slot, Hero $hero)
    {
        if ($slot->hasSlots->getUniqueIdentifier() != $hero->getUniqueIdentifier()) {
            throw new SlottingException($slot, $hero, "Slot does not belong to Hero", SlottingException::CODE_INVALID_OWNERSHIP);
        }

        $item = $slot->item;
        if (! $item) {
            throw new SlottingException($slot, $hero, "Slot is already empty", SlottingException::CODE_ALREADY_EMPTY);
        }

        $squad = $hero->getSquad();
        if (! $squad) {
            throw new SlottingException($slot, $hero, "No squad available for hero", SlottingException::CODE_NO_BACKUP);
        }

        $filledSlots = $hero->slots->filledWithItem($item);
        $filledSlots->emptyItems();

        $this->slotItemInSquadAction->execute($squad, $item);
    }
}
