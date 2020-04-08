<?php


namespace App\Domain\Actions;


use App\Domain\Models\PlayerSpirit;
use Illuminate\Support\Facades\Date;

class DisablePlayerSpirit
{
    public function execute(PlayerSpirit $playerSpirit)
    {
        $playerSpirit->disabled_at = Date::now();
        $playerSpirit->save();

        // TODO: handle heroes using disabled spirit
    }
}
