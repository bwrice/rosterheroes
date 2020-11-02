<?php


namespace App\Domain\Actions\Snapshots;


use App\Facades\CurrentWeek;

abstract class BuildSnapshot
{
    public const EXCEPTION_CODE_WEEK_NOT_FINALIZING = 1;

    protected bool $weekly = true;

    /**
     * @param mixed ...$args
     * @throws \Exception
     * @return mixed
     */
    public function execute(...$args)
    {
        if ($this->weekly && ! CurrentWeek::finalizing()) {
            throw new \Exception("Cannot create snapshot unless week is finalizing", self::EXCEPTION_CODE_WEEK_NOT_FINALIZING);
        }

        return call_user_func([$this, 'handle'], ...$args);
    }

    public function nonWeekly()
    {
        $this->weekly = false;
        return $this;
    }
}
