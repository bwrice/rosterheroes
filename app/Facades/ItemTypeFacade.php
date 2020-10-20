<?php


namespace App\Facades;


use App\Services\Models\Reference\ItemTypeService;
use Illuminate\Support\Facades\Facade;

/**
 * Class ItemTypeFacade
 * @package App\Facades
 *
 * @method static int tier(int $itemTypeID)
 */
class ItemTypeFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ItemTypeService::class;
    }
}
