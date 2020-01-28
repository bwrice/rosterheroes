<?php


namespace App\Services;


use App\Domain\Models\Hero;

class HeroCombat
{
    public function notReadyReasons(Hero $hero)
    {
        $reasons = [];
        if (! $hero->playerSpirit) {
            $reasons['player_spirit'] = "No player spirit";
        }
        return $reasons;
    }
}
