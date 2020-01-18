<?php


namespace App\Facades;


use App\Domain\Models\Week;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Facade;

/**
 * Class WeekService
 * @package App\Facades
 *
 * @method static CarbonPeriod getValidGamePeriod(Week $week)
 */
class WeekService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'week-service';
    }
}
