<?php


namespace App\Domain\Actions;


use App\Domain\Models\Week;
use App\Exceptions\BuildWeekException;

class BuildFirstWeekAction
{
    public function execute()
    {
        $currentWeek = Week::current();
        if ($currentWeek) {
            throw new BuildWeekException("There should be no current week when building the first week", BuildWeekException::CODE_INVALID_CURRENT_WEEK);
        }
    }
}
