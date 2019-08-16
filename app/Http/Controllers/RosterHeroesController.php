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
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(Squad::MANAGE_AUTHORIZATION, $squad);
        $heroes = Hero::query()->amongSquad($squad)->get();
        $heroes->load([
            'heroRace.positions',
            'heroClass',
            'playerSpirit.game.homeTeam',
            'playerSpirit.game.awayTeam',
            'playerSpirit.player.team',
            'playerSpirit.player.positions'
        ]);
        return HeroResource::collection($heroes);
    }
}
