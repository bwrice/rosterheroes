<?php


namespace App\Services\Models;


use App\Domain\Models\Hero;

/**
 * Class HeroService
 * @package App\Services
 *
 * @see \App\Facades\HeroService
 */
class HeroService
{
    public function combatUnReadyReasons(Hero $hero)
    {
        $reasons = [];
        if (! $hero->playerSpirit) {
            $reasons['player_spirit'] = "No player spirit";
        }
        return $reasons;
    }

    public function combatReady(Hero $hero)
    {
        return count($this->combatUnReadyReasons($hero)) === 0;
    }
}
