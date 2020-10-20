<?php


namespace App\Facades;


use App\Domain\Behaviors\ItemBases\ItemBaseBehavior;
use App\Services\Models\Reference\ItemTypeService;
use Illuminate\Support\Facades\Facade;

/**
 * Class ItemTypeFacade
 * @package App\Facades
 *
 * @method static int tier(int $itemTypeID)
 * @method static float baseDamageBonus(int $itemTypeID)
 * @method static ItemBaseBehavior baseBehavior(int $itemTypeID)
 */
class ItemTypeFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ItemTypeService::class;
    }
}
