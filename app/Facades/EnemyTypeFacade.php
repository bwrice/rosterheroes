<?php


namespace App\Facades;


use App\Domain\Behaviors\EnemyTypes\EnemyTypeBehavior;
use App\Services\Models\Reference\EnemyTypeService;
use Illuminate\Support\Facades\Facade;

/**
 * Class EnemyTypeFacade
 * @package App\Facades
 *
 * @method static EnemyTypeBehavior getBehavior($identifier)
 */
class EnemyTypeFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return EnemyTypeService::class;
    }
}
