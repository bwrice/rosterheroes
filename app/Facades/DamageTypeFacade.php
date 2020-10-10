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
 * @method static int maxTargetsCount(int|string $identifier, int $tier, int $targetsCount = null)
 * @method static int damagePerTarget(int|string $identifier, int $totalDamage, int $targetsCount)
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
