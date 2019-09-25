<?php


namespace App\Exceptions;


use App\Domain\Interfaces\HasSlots;
use App\Domain\Models\Hero;
use App\Domain\Models\Slot;
use Throwable;

class SlottingException extends \RuntimeException
{

    public const CODE_INVALID_OWNERSHIP = 1;
    public const CODE_ALREADY_EMPTY = 2;
    public const CODE_NO_BACKUP = 3;

    /**
     * @var Slot
     */
    private $slot;

    /**
     * @var HasSlots|null
     */
    private $hasSlots;

    public function __construct(Slot $slot, HasSlots $hasSlots = null, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->slot = $slot;
        $this->hasSlots = $hasSlots;
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
}
