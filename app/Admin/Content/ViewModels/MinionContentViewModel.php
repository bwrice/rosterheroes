<?php


namespace App\Admin\Content\ViewModels;


use App\Facades\Content;
use Carbon\CarbonInterface;

class MinionContentViewModel implements ContentViewModel
{

    public function getTitle(): string
    {
        return 'Minions';
    }

    public function totalCount(): int
    {
        return Content::minions()->count();
    }

    public function outOfSynCount(): int
    {
        return Content::unSyncedMinions()->count();
    }

    public function lastUpdated(): CarbonInterface
    {
        return Content::minionsLastUpdated();
    }
}
