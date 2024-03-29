<?php


namespace App\Facades;

use App\Admin\Content\ViewModels\ContentViewModel;
use App\Services\ContentService;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * Class Content
 * @package App\Facades
 *
 * @method static string viewURL(ContentViewModel $viewModel)
 * @method static string createURL(ContentViewModel $viewModel)
 * @method static string syncURL(ContentViewModel $viewModel)
 *
 * @method static Collection attacks()
 * @method static Collection unSyncedAttacks()
 * @method static CarbonInterface attacksLastUpdated()
 * @method static string attacksPath()
 *
 * @method static Collection itemTypes()
 * @method static Collection unSyncedItemTypes()
 * @method static CarbonInterface itemTypesLastUpdated()
 * @method static string itemTypesPath()
 *
 * @method static Collection itemBlueprints()
 * @method static Collection unSyncedItemBlueprints()
 * @method static CarbonInterface itemBlueprintsLastUpdated()
 * @method static string itemBlueprintsPath()
 *
 * @method static Collection chestBlueprints()
 * @method static Collection unSyncedChestBlueprints()
 * @method static CarbonInterface chestBlueprintsLastUpdated()
 * @method static string chestBlueprintsPath()
 *
 * @method static Collection minions()
 * @method static Collection unSyncedMinions()
 * @method static CarbonInterface minionsLastUpdated()
 * @method static string minionsPath()
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
