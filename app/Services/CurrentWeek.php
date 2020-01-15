<?php


namespace App\Services;


use App\Domain\Models\Week;
use Illuminate\Support\Facades\Date;

class CurrentWeek
{
    public const FINALIZE_AFTER_ADVENTURING_CLOSED_HOURS = 15;

    protected static $testCurrent = null;

    /**
     * @return Week|null
     */
    public function get()
    {
        return self::$testCurrent ?? Week::query()->current();
    }

    /**
     * @param Week $week
     * @return $this
     */
    public function setTestCurrent(Week $week)
    {
        self::$testCurrent = $week;

        return $this;
    }

    /**
     * @return int
     */
    public function id()
    {
        return $this->get()->id;
    }

    /**
     * @return bool
     */
    public function finalizing()
    {
        return Date::now()->isAfter($this->finalizingStartsAt());
    }

    /**
     * @return \Carbon\CarbonImmutable
     */
    public function finalizingStartsAt()
    {
        return $this->get()->adventuring_locks_at->addHours(self::FINALIZE_AFTER_ADVENTURING_CLOSED_HOURS);
    }

    /**
     * @return bool
     */
    public function exists()
    {
        return ! is_null($this->get());
    }

    /**
     * @return bool
     */
    public function adventuringOpen()
    {
        return Date::now()->isBefore($this->get()->adventuring_locks_at);
    }

    /**
     * @return bool
     */
    public function adventuringLocked()
    {
        return ! $this->adventuringOpen();
    }
}
