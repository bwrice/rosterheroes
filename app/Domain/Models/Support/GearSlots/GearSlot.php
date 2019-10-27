<?php


namespace App\Domain\Models\Support\GearSlots;


use App\Domain\Interfaces\FillsGearSlots;
use App\Domain\Models\Item;

abstract class GearSlot
{
    public const PRIMARY_ARM = 'Primary Arm';
    public const OFF_ARM = 'Off-Arm';
    public const HEAD = 'Head';
    public const TORSO = 'Torso';
    public const LEGS = 'Legs';
    public const FEET = 'Feet';
    public const HANDS = 'Hands';
    public const WAIST = 'Waist';
    public const NECK = 'Neck';
    public const PRIMARY_WRIST = 'Primary Wrist';
    public const OFF_WRIST = 'Off-Wrist';
    public const RING_ONE = 'Ring One';
    public const RING_TWO = 'Ring Two';

    /** @var FillsGearSlots|null */
    protected $filler;

    /** @var string */
    protected $type = '';

    /**
     * @param FillsGearSlots|null $fillsGearSlots
     * @return GearSlot
     */
    public function setFiller(?FillsGearSlots $fillsGearSlots): GearSlot
    {
        $this->filler = $fillsGearSlots;
        return $this;
    }


    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return FillsGearSlots|null
     */
    public function getFiller(): ?FillsGearSlots
    {
        return $this->filler;
    }
}
