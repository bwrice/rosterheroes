<?php


namespace App\Admin\Content\ViewModels;


use App\Facades\Content;
use Carbon\CarbonInterface;

class ChestBlueprintContentViewModel implements ContentViewModel
{

    public function getTitle(): string
    {
        return 'Chest Blueprints';
    }

    public function totalCount(): int
    {
        return Content::chestBlueprints()->count();
    }

    public function outOfSynCount(): int
    {
        return Content::unSyncedChestBlueprints()->count();
    }

    public function lastUpdated(): CarbonInterface
    {
        return Content::chestBlueprintsLastUpdated();
    }
}
