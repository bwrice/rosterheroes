<?php


namespace App\Facades;


use App\Domain\Behaviors\MeasurableTypes\MeasurableTypeBehavior;
use App\Services\Models\Reference\MeasurableTypeService;
use Illuminate\Support\Facades\Facade;

/**
 * Class MeasurableTypeFacade
 * @package App\Facades
 *
 * @method static string name(int $id)
 * @method static int id(string $name)
 * @method static MeasurableTypeBehavior getBehavior($identifier)
 *
 */
class MeasurableTypeFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return MeasurableTypeService::class;
    }
}
