<?php

namespace App\Exceptions;

use App\Weeks\Week;
use Carbon\Carbon;
use Exception;
use Throwable;

class WeekLockedException extends Exception
{
    /**
     * @var Week
     */
    private $week;

    public function __construct(Week $week, $message = "", int $code = 0, Throwable $previous = null)
    {
        $message = $message ?: "That action is locked for week: " . $week->name;
        $this->week = $week;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return Week
     */
    public function getWeek(): Week
    {
        return $this->week;
    }

}