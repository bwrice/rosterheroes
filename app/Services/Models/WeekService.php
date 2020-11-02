<?php


namespace App\Services\Models;


use App\Domain\Models\Week;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;

class WeekService
{
    public const FINALIZE_AFTER_ADVENTURING_CLOSED_HOURS = 16;

    /**
     * @param CarbonInterface $adventuringLocksAt
     * @return CarbonPeriod
     */
    public function getValidGamePeriod(CarbonInterface $adventuringLocksAt)
    {
        $start = $adventuringLocksAt->clone();
        $end = $start->addHours(self::FINALIZE_AFTER_ADVENTURING_CLOSED_HOURS - 4);
        return new CarbonPeriod($start, $end);
    }

    /**
     * @param CarbonInterface $adventuringLocksAt
     * @return CarbonInterface
     */
    public function finalizingStartsAt(CarbonInterface $adventuringLocksAt)
    {
        return $adventuringLocksAt->clone()->addHours(self::FINALIZE_AFTER_ADVENTURING_CLOSED_HOURS);
    }
}
