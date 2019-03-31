<?php

namespace App\Exceptions;

use App\Domain\Models\Week;
use Carbon\Carbon;
use Exception;
use Throwable;

class WeekLockedException extends Exception
{
    /**
     * @var \App\Domain\Models\Week
     */
    private $week;

    public function __construct(Week $week, $message = "", int $code = 0, Throwable $previous = null)
    {
        $message = $message ?: "That action is locked for week: " . $week->name;
        $this->week = $week;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return \App\Domain\Models\Week
     */
    public function getWeek(): Week
    {
        return $this->week;
    }

}