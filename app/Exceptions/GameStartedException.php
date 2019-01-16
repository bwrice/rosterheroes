<?php

namespace App\Exceptions;

use Carbon\Carbon;
use Exception;
use Throwable;

class GameStartedException extends Exception
{
    public function __construct($message = "", int $code = 0, Throwable $previous = null)
    {
        $message = $message ?: "Game started before: " . Carbon::now()->format('Y-m-d H:i:s');
        parent::__construct($message, $code, $previous);
    }

    public function render()
    {
        return "Game has started";
    }
}
