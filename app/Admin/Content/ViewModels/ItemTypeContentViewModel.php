<?php


namespace App\Admin\Content\ViewModels;


use App\Facades\Content;
use Carbon\CarbonInterface;

class ItemTypeContentViewModel implements ContentViewModel
{

    public function getTitle(): string
    {
        return 'Item Types';
    }

    public function totalCount(): int
    {
        return Content::itemTypes()->count();
    }

    public function outOfSynCount(): int
    {
        return Content::unSyncedItemTypes()->count();
    }

    public function lastUpdated(): CarbonInterface
    {
        return Content::itemTypesLastUpdated();
    }

    public function createURL(): string
    {
        return '/admin/content/item-types/create';
    }

    public function syncActionURL(): string
    {
        return '/admin/content/item-types/sync';
    }
}
