<?php


namespace App\Facades;


use App\Domain\Models\Week;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Facade;

/**
 * Class CurrentWeek
 * @package App\Facades
 *
 * @method static Week get()
 * @method static bool finalizing()
 * @method static int id()
 * @method static CurrentWeek setTestCurrent(Week $week)
 * @method static CurrentWeek setTestFinalizing(bool $finalizing)
 * @method static CarbonImmutable adventuringLocksAt()
 * @method static CarbonImmutable finalizingStartsAt()
 * @method static CarbonPeriod validGamePeriod()
 * @method static bool exists()
 * @method static bool adventuringOpen()
 * @method static bool adventuringLocked()
 * @method static void clearTestCurrent()
 *
 * @see \App\Services\CurrentWeek
 */
class CurrentWeek extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'current-week';
    }
}
