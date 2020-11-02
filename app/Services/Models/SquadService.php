<?php


namespace App\Services\Models;


use App\Domain\Models\Hero;
use App\Domain\Models\Squad;

class SquadService
{
    public function combatReady(Squad $squad)
    {
        $readyHero = $squad->heroes->first(function (Hero $hero) {
            return \App\Facades\HeroService::combatReady($hero);
        });
        return ! is_null($readyHero);
    }

    public function inCreationState(Squad $squad)
    {
        return $squad->heroes()->count() < Squad::getStartingHeroesCount();
    }
}
