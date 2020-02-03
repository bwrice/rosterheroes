<?php


namespace App\Services\ModelServices;


use App\Domain\Models\SideQuest;

class SkirmishService
{
    public function query()
    {
        return SideQuest::query();
    }
}
