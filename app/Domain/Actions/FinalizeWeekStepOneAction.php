<?php


namespace App\Domain\Actions;


use App\Domain\Models\Week;
use App\Exceptions\StepOneException;
use Bwrice\LaravelJobChainGroups\Jobs\ChainGroup;
use Illuminate\Support\Facades\Date;

class FinalizeWeekStepOneAction
{
    public function execute(Week $week)
    {
        $this->validateTime($week);
//        ChainGroup::create([
//
//        ]);
    }

    protected function validateTime(Week $week)
    {
        if (Date::now()->isBefore($week->adventuring_locks_at->addHours(Week::FINALIZE_AFTER_ADVENTURING_CLOSED_HOURS))) {
            throw new StepOneException($week, "Week is not ready to be finalized", StepOneException::INVALID_TIME_TO_FINALIZE);
        }
    }
}
