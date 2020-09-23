<?php


namespace App\Facades;


use App\Services\Models\Reference\DamageTypeService;
use Illuminate\Support\Facades\Facade;

/**
 * Class DamageTypeFacade
 * @package App\Facades
 *
 * @method static getBehavior(int|string $identifier)
 *
 * @see DamageTypeService
 */
class DamageTypeFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return DamageTypeService::class;
    }
}
