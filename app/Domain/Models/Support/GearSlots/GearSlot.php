<?php


namespace App\Domain\Models\Support\GearSlots;


use App\Domain\Models\Item;

abstract class GearSlot
{
    public const PRIMARY_ARM = 'primary-arm';
    public const OFF_ARM = 'off-arm';
    public const HEAD = 'head';
    public const TORSO = 'torso';
    public const LEGS = 'legs';
    public const FEET = 'feet';
    public const HANDS = 'hands';
    public const WAIST = 'waist';
    public const NECK = 'neck';
    public const PRIMARY_WRIST = 'primary-wrist';
    public const OFF_WRIST = 'off-wrist';
    public const RING_ONE = 'ring-one';
    public const RING_TWO = 'ring-two';

    /** @var Item|null */
    protected $item;

    /**
     * @param Item|null $item
     * @return GearSlot
     */
    public function setItem(?Item $item): GearSlot
    {
        $this->item = $item;
        return $this;
    }
}
