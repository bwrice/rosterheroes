<?php


namespace App\Domain\Interfaces;


interface FillsGearSlots
{
    public function getValidGearSlotTypes(): array;

    public function getGearSlotsNeededCount(): int;
}
