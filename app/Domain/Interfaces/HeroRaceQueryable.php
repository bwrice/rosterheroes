<?php


namespace App\Domain\Interfaces;


use App\Domain\Models\HeroRace;
use Illuminate\Database\Eloquent\Builder;

interface HeroRaceQueryable
{
    public function forHeroRace(string $heroRaceName): Builder;
}