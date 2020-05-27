<?php


namespace App\Domain\Actions;


use App\Domain\Models\Hero;
use App\Facades\CurrentWeek;

class ClearWeeklyPlayerSpirit
{
    public function execute(Hero $hero)
    {
        $playerSpirit = $hero->playerSpirit;
        if (is_null($playerSpirit)) {
            throw new \Exception("No player-spirit attached to hero with id: " . $hero->id);
        }
        if (CurrentWeek::id() === $playerSpirit->week_id && ! CurrentWeek::finalizing()) {
            throw new \Exception("Week not finalizing: Cannot remove player-spirit for hero: " . $hero->id);
        }
        $hero->player_spirit_id = null;
        $hero->save();
    }
}
