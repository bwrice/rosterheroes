<?php

namespace App\Exceptions;

use App\Weeks\Week;
use App\Weeks\WeekCollection;
use Exception;

class InvalidWeekException extends \RuntimeException
{
    protected $invalidWeek;

    protected $validWeeks;

    public function setWeeks(Week $invalidWeek, WeekCollection $validWeeks)
    {
        $this->message = "Invalid week: " . $invalidWeek->name;
        $this->invalidWeek = $invalidWeek;
        $this->validWeeks = $validWeeks;
    }

    /**
     * @return Week
     */
    public function getInvalidWeek()
    {
        return $this->invalidWeek;
    }

    /**
     * @return WeekCollection
     */
    public function getValidWeeks()
    {
        return $this->validWeeks;
    }
}
