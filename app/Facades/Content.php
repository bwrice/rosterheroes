<?php


namespace App\Facades;

use App\Services\ContentService;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * Class Content
 * @package App\Facades
 *
 * @method static Collection attacks()
 * @method static Collection unSyncedAttacks()
 * @method static CarbonInterface attacksLastUpdated()
 * @method static string attacksPath()
 *
 * @see ContentService
 */
class Content extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'content';
    }
}
