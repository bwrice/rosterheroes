<?php


namespace App\Exceptions;


use App\Domain\Models\Slot;
use Throwable;

class EmptySlotException extends \RuntimeException
{
    public const CODE_ALREADY_EMPTY = 1;
    public const NO_BACKUP_EXCEPTION = 2;

    public function __construct(Slot $slot, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
