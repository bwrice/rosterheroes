<?php


namespace App\Exceptions;


use App\Domain\Interfaces\HasSlots;
use App\Domain\Interfaces\Slottable;
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
     * @var Slottable
     */
    private $slottable;

    public function __construct(HasSlots $hasSlots, Slottable $slottable, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->hasSlots = $hasSlots;
        $this->slottable = $slottable;
    }

    /**
     * @return HasSlots
     */
    public function getHasSlots(): HasSlots
    {
        return $this->hasSlots;
    }

    /**
     * @return Slottable
     */
    public function getSlottable(): Slottable
    {
        return $this->slottable;
    }
}
