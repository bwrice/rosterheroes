<?php


namespace App\Facades;


use App\Domain\Models\Week;
use Illuminate\Support\Facades\Facade;

/**
 * Class CurrentWeek
 * @package App\Facades
 *
 * @method static Week get()
 * @method static bool finalizing()
 * @method static int id()
 * @method static CurrentWeek setTestCurrent(Week $week)
 */
class CurrentWeek extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'current-week';
    }
}
