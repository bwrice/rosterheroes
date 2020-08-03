<?php


namespace App\Domain\Actions;


use App\Domain\Models\Week;
use App\Facades\CurrentWeek;
use App\Facades\WeekService;
use App\Jobs\FinalizeWeekJob;

class DispatchUpcomingFinalizeWeekJob
{
    public function execute(Week $week = null)
    {
        $week = $week ?: CurrentWeek::get();
        $step = 1;
        $extraHoursToAdd = config('week.finalize_extra_hours_delay');
        $delay = WeekService::finalizingStartsAt($week->adventuring_locks_at)->clone()->addHours($extraHoursToAdd);
        FinalizeWeekJob::dispatch($step)->delay($delay);
    }
}
