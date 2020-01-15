<?php


namespace App\Facades;


use App\Domain\Models\Week;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Facade;

/**
 * Class CurrentWeek
 * @package App\Facades
 *
 * @method static Week get()
 * @method static bool finalizing()
 * @method static int id()
 * @method static CurrentWeek setTestCurrent(Week $week)
 * @method static CarbonImmutable finalizingStartsAt()
 * @method static bool exists()
 * @method static bool adventuringOpen()
 * @method static bool adventuringLocked()
 */
class CurrentWeek extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'current-week';
    }
}
