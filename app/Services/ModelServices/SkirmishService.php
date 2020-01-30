<?php


namespace App\Services\ModelServices;


use App\Domain\Models\Skirmish;

class SkirmishService
{
    public function query()
    {
        return Skirmish::query();
    }
}
