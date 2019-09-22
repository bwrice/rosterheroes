<?php


namespace App\Domain\Actions;


use App\Domain\Models\Slot;
use App\Exceptions\EmptySlotException;
use App\Exceptions\FillSlotException;

//TODO decide if needed or delete
class EmptySlotAction
{
    /**
     * @var FillSlotsWithItemAction
     */
    private $fillSlotsWithItemAction;

    /**
     * EmptySlotAction constructor.
     * @param FillSlotsWithItemAction $fillSlotsWithItemAction
     */
    public function __construct(FillSlotsWithItemAction $fillSlotsWithItemAction)
    {
        $this->fillSlotsWithItemAction = $fillSlotsWithItemAction;
    }

    /**
     * @param Slot $slot
     * @throws FillSlotException
     */
    public function execute(Slot $slot)
    {
        $item = $slot->item;
        if (! $item) {
            throw new EmptySlotException($slot, 'Slot is already empty', EmptySlotException::CODE_ALREADY_EMPTY);
        }

        $hasSlots = $slot->hasSlots;
        $backup = $hasSlots->getBackupHasSlots();
        if ($backup) {
            throw new EmptySlotException($slot, 'Cannot empty without a backup', EmptySlotException::NO_BACKUP_EXCEPTION);
        }

        $this->fillSlotsWithItemAction->execute($backup, $item);
    }
}
