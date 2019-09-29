<?php


namespace App\Exceptions;


use App\Domain\Interfaces\HasSlots;
use App\Domain\Models\Hero;
use App\Domain\Models\Item;
use App\Domain\Models\Slot;
use Throwable;

class SlottingException extends \RuntimeException
{
    public const CODE_INVALID_SLOT_OWNERSHIP = 1;
    public const CODE_INVALID_ITEM_OWNERSHIP = 2;
    public const CODE_ALREADY_EMPTY = 3;
    public const CODE_NO_BACKUP = 4;
    public const CODE_INVALID_SLOT_TYPE = 5;

    /**
     * @var Slot
     */
    private $slot;

    /**
     * @var HasSlots|null
     */
    private $hasSlots;

    /**
     * @var Item
     */
    private $item;

    public function __construct(Slot $slot, HasSlots $hasSlots = null, Item $item = null, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->slot = $slot;
        $this->hasSlots = $hasSlots;
        $this->item = $item;
    }

    /**
     * @return Slot
     */
    public function getSlot(): Slot
    {
        return $this->slot;
    }

    /**
     * @return HasSlots|null
     */
    public function getHasSlots(): ?HasSlots
    {
        return $this->hasSlots;
    }

    /**
     * @return Item|null
     */
    public function getItem(): ?Item
    {
        return $this->item;
    }
}
