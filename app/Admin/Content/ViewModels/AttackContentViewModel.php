<?php


namespace App\Admin\Content\ViewModels;

use App\Facades\Content;
use Carbon\CarbonInterface;

class AttackContentViewModel implements ContentViewModel
{

    public function getTitle(): string
    {
        return 'Attacks';
    }

    public function totalCount(): int
    {
        return Content::attacks()->count();
    }

    public function outOfSynCount(): int
    {
        return Content::unSyncedAttacks()->count();
    }

    public function lastUpdated(): CarbonInterface
    {
        return Content::attacksLastUpdated();
    }
}
