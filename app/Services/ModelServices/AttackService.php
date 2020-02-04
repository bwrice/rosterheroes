<?php


namespace App\Services\ModelServices;


use App\Domain\Models\Attack;

class AttackService
{
    public function all()
    {
        return Attack::all();
    }

    public function query()
    {
        return Attack::query();
    }
}
