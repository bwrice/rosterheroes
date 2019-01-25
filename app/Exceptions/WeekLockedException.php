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
    /**
     * @var Carbon
     */
    private $lockedAt;

    public function __construct(Week $week, Carbon $lockedAt, $message = "", int $code = 0, Throwable $previous = null)
    {
        $message = $message ?: "That action locked at " . $lockedAt->diffForHumans() . " for week: " . $week->name;
        $this->week = $week;
        $this->lockedAt = $lockedAt;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return Week
     */
    public function getWeek(): Week
    {
        return $this->week;
    }

    /**
     * @return Carbon
     */
    public function getLockedAt(): Carbon
    {
        return $this->lockedAt;
    }
}