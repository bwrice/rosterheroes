<?php


namespace App\Exceptions;


use App\Domain\Interfaces\HasSlots;
use App\Domain\Interfaces\Slottable;
use App\Domain\Models\Item;
use Throwable;

class FillSlotException extends \Exception
{
    public const CODE_SECOND_ATTEMPT = 1;
    public const NO_BACKUP = 2;

    /**
     * @var HasSlots
     */
    private $hasSlots;
    /**
     * @var Item
     */
    private $item;

    public function __construct(HasSlots $hasSlots, Item $item, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->hasSlots = $hasSlots;
        $this->$item = $item;
    }

    /**
     * @return HasSlots
     */
    public function getHasSlots(): HasSlots
    {
        return $this->hasSlots;
    }

    /**
     * @return Item
     */
    public function getItem(): Item
    {
        return $this->item;
    }
}
