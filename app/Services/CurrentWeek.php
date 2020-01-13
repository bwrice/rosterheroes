<?php


namespace App\Services;


use App\Domain\Models\Week;
use Illuminate\Support\Facades\Date;

class CurrentWeek
{
    public const FINALIZE_AFTER_ADVENTURING_CLOSED_HOURS = 15;

    public function get()
    {
        return Week::query()->current();
    }

    public function finalizing()
    {
        return Date::now()->isAfter($this->get()->adventuring_locks_at->addHours(self::FINALIZE_AFTER_ADVENTURING_CLOSED_HOURS));
    }
}
