<?php


namespace App\Admin\Content\ViewModels;


use App\Facades\Content;
use Carbon\CarbonInterface;

class ItemBlueprintContentViewModel implements ContentViewModel
{

    public function getTitle(): string
    {
        return 'Item Blueprints';
    }

    public function totalCount(): int
    {
        return Content::itemBlueprints()->count();
    }

    public function outOfSynCount(): int
    {
        return Content::unSyncedItemBlueprints()->count();
    }

    public function lastUpdated(): CarbonInterface
    {
        return Content::itemBlueprintsLastUpdated();
    }
}
