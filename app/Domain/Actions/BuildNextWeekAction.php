<?php


namespace App\Domain\Actions;


use App\Domain\Models\Week;
use App\Exceptions\BuildNextWeekException;
use App\Exceptions\BuildWeekException;

class BuildNextWeekAction
{
    public function execute()
    {
        $currentWeek = Week::current();
        if (! $currentWeek) {
            throw new BuildNextWeekException("No current week to build next week from", BuildWeekException::CODE_INVALID_CURRENT_WEEK);
        }
    }
}
