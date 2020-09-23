<?php


namespace App\Facades;


use App\Domain\Behaviors\DamageTypes\DamageTypeBehavior;
use App\Services\Models\Reference\DamageTypeService;
use Illuminate\Support\Facades\Facade;

/**
 * Class DamageTypeFacade
 * @package App\Facades
 *
 * @method static DamageTypeBehavior getBehavior(int|string $identifier)
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
