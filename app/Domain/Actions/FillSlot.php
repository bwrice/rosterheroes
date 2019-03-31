<?php
/**
 * Created by PhpStorm.
 * User: bwrice
 * Date: 12/16/18
 * Time: 5:15 PM
 */

namespace App\Domain\Actions;


use App\Domain\Interfaces\HasSlots;
use App\Domain\Interfaces\Slottable;

class FillSlot
{
    /**
     * @var HasSlots
     */
    private $hasSlots;
    /**
     * @var Slottable
     */
    private $slottable;
    /**
     * @var bool
     */
    private $secondAttempt;

    public function __construct(HasSlots $hasSlots, Slottable $slottable, $secondAttempt = false)
    {
        $this->hasSlots = $hasSlots;
        $this->slottable = $slottable;
        $this->secondAttempt = $secondAttempt;
    }

    public function __invoke()
    {

        $slotTypeIDs = $this->slottable->getSlotTypeIDs();
        $slotsNeededCount = $this->slottable->getSlotsCount();
        $emptySlots = $this->hasSlots->getEmptySlots($slotsNeededCount, $slotTypeIDs);

        $slotsToEmptyCount = $slotsNeededCount - $emptySlots->count();

        if ($slotsToEmptyCount > 0) {

            if ($this->secondAttempt) {
                throw new \RuntimeException("Not enough empty slots on 2nd attempt of equipping");
            }

            $backup = $this->hasSlots->getBackupHasSlots();

            if (! $backup) {
                throw new \RuntimeException("Not enough empty slots with no back-up available");
            }

            // Get slots we need to remove from their slots to make space for what we intend to slot
            $slottables = $this->hasSlots->getSlots()->filled()->take($slotsToEmptyCount)->getSlottables();
            // Now get the slots those slottables are currently slotted into and empty them
            $slottables->getSlots()->emptySlottables();

            // Re-slot the slottables that were removed
            $slottables->each(function (Slottable $slottable) use ($backup) {
                $action = (new static($backup, $slottable, false));
                $action(); //invoke
            });

            //Now try to slot the original slottable again
            $action = (new static($this->hasSlots->getFresh(), $this->slottable, true));
            $action(); //invoke

        } else {
            $slots = $emptySlots->take($slotsNeededCount);
            $this->slottable->slots()->saveMany($slots);
        }
    }

}