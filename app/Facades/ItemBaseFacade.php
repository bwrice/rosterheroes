<?php


namespace App\Facades;


use App\Domain\Behaviors\ItemBases\ItemBaseBehavior;
use App\Services\Models\Reference\ItemBaseService;
use Illuminate\Support\Facades\Facade;

/**
 * Class ItemBaseFacade
 * @package App\Facades
 *
 * @method static ItemBaseBehavior getBehavior($identifier)
 */
class ItemBaseFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ItemBaseService::class;
    }
}
