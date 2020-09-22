<?php


namespace App\Facades;


use App\Domain\Models\Week;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Facade;

/**
 * Class WeekService
 * @package App\Facades
 *
 * @method static CarbonPeriod getValidGamePeriod(CarbonInterface $adventuringLocksAt)
 * @method static CarbonImmutable finalizingStartsAt(CarbonInterface $adventuringLocksAt)
 *
 * @see \App\Services\Models\WeekService
 */
class WeekService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'week-service';
    }
}
