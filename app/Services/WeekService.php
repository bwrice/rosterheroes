<?php


namespace App\Services;


use App\Domain\Models\Week;
use Carbon\CarbonPeriod;

class WeekService
{
    public const FINALIZE_AFTER_ADVENTURING_CLOSED_HOURS = 16;

    /**
     * @param Week $week
     * @return CarbonPeriod
     */
    public function getValidGamePeriod(Week $week)
    {
        $start = $week->adventuring_locks_at;
        $end = $start->addHours(self::FINALIZE_AFTER_ADVENTURING_CLOSED_HOURS - 4);
        return new CarbonPeriod($start, $end);
    }
}
