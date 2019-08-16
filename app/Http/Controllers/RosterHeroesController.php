<?php

namespace App\Http\Controllers;

use App\Domain\Models\Hero;
use App\Domain\Models\Squad;
use App\Http\Resources\HeroResource;
use Illuminate\Http\Request;

class RosterHeroesController extends Controller
{
    public function __invoke($squadSlug)
    {
        $squad = Squad::slugOrFail($squadSlug);
        $this->authorize(Squad::MANAGE_AUTHORIZATION, $squad);
        $heroes = Hero::query()->amongSquad($squad)->get();
        $heroes->load([
            'hero.heroRace.positions',
            'hero.heroClass',
            'hero.playerSpirit.game.homeTeam',
            'hero.playerSpirit.game.awayTeam',
            'hero.playerSpirit.player.team',
            'hero.playerSpirit.player.positions'
        ]);
        return HeroResource::collection($heroes);
    }
}
