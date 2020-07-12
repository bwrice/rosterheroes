<?php


namespace App\Facades;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * Class Content
 * @package App\Facades
 *
 * @method static Collection attacks()
 * @method static CarbonInterface attacksLastUpdated()
 */
class Content extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'content';
    }
}
