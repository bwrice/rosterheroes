<?php

namespace App\Exceptions;

use App\Domain\Models\Week;
use App\Domain\Collections\WeekCollection;
use Exception;
use Throwable;

class InvalidWeekException extends \RuntimeException
{
    /**
     * @var Week
     */
    private $week;

    public function __construct(Week $week, string $message = "", int $code = 0, Throwable $previous = null)
    {
        $this->week = $week;
        $message = $message ?: "Invalid Week with ID: " . $week->id;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return \App\Domain\Models\Week
     */
    public function getWeek()
    {
        return $this->week;
    }
}
