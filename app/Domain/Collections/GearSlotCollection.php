<?php


namespace App\Domain\Collections;


use App\Domain\Interfaces\FillsGearSlots;
use App\Domain\Models\Support\GearSlots\GearSlot;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class GearSlotCollection extends Collection
{
    public function setSlotFillers(Collection $slotFillers)
    {
        $slotFillers->each(function (FillsGearSlots $fillsGearSlots) {
            $availableGearSlots = $this->slotEmpty()->forGearSlotTypes($fillsGearSlots->getValidGearSlotTypes());
            $slotsNeededCount = $fillsGearSlots->getGearSlotsNeededCount();
            if ($availableGearSlots->count() < $slotsNeededCount) {
                Log::warning("Available gear slots less than needed when filling for: " . $fillsGearSlots->getUuid());
            }
            $availableGearSlots->take($slotsNeededCount)->each(function (GearSlot $gearSlot) use ($fillsGearSlots) {
                $gearSlot->setFiller($fillsGearSlots);
            });
        });
    }

    public function slotEmpty()
    {
        return $this->filter(function (GearSlot $gearSlot) {
            return is_null($gearSlot->getFiller());
        });
    }

    public function forGearSlotTypes(array $gearSlotTypes)
    {
        return $this->filter(function (GearSlot $gearSlot) use ($gearSlotTypes) {
            return in_array($gearSlot->getType(), $gearSlotTypes);
        });
    }
}
