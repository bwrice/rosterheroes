<?php

namespace App\Exceptions;

use Carbon\Carbon;
use Exception;
use Throwable;

class NoCurrentWeekException extends Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        $message = $message ?: "No current week for " . Carbon::now()->format('Y-m-d H:i:s');
        parent::__construct($message, $code, $previous);
    }
}
