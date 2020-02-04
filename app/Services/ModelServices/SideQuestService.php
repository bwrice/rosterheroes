<?php


namespace App\Services\ModelServices;


use App\Domain\Models\SideQuest;

class SideQuestService
{
    public function query()
    {
        return SideQuest::query();
    }
}
