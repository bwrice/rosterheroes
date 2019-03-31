<?php

namespace App\Exceptions;

use App\Domain\Models\Week;
use App\Domain\Collections\WeekCollection;
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
     * @return \App\Domain\Models\Week
     */
    public function getInvalidWeek()
    {
        return $this->invalidWeek;
    }

    /**
     * @return \App\Domain\Collections\WeekCollection
     */
    public function getValidWeeks()
    {
        return $this->validWeeks;
    }
}
