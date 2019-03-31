<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 3/31/19
 * Time: 4:09 PM
 */

namespace App\Domain\Actions;


use App\Domain\Slot;

class EmptySlot
{
    /**
     * @var Slot
     */
    private $slotToEmpty;
    /**
     * @var FillSlot
     */
    private $fillSlotAction;

    public function __construct(Slot $slotToEmpty, FillSlot $fillSlotAction)
    {
        $this->slotToEmpty = $slotToEmpty;
        $this->fillSlotAction = $fillSlotAction;
    }

    public function __invoke()
    {
        $this->slotToEmpty->slottable()->dissociate();
        call_user_func($this->fillSlotAction);
    }
}