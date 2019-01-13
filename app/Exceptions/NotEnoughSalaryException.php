<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class NotEnoughSalaryException extends \RuntimeException
{
    public function __construct($salaryAvailable, $salaryNeeded, int $code = 0, Throwable $previous = null)
    {
        $message = $salaryNeeded . " salary needed, but only " . $salaryAvailable . " salary available";
        parent::__construct($message, $code, $previous);
    }

}
