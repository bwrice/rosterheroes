<?php

namespace App\Http\Controllers;

use App\Domain\Models\Hero;
use App\Http\Resources\SquadCreationHeroResource;
use Illuminate\Http\Request;

class HeroController extends Controller
{
    public function show($heroSlug)
    {
        // TODO: auth
        $hero = Hero::findSlugOrFail($heroSlug);
        return new SquadCreationHeroResource($hero->loadMissing([
            'heroClass',
            'heroRace.positions',
            'playerSpirit.game.homeTeam',
            'playerSpirit.game.awayTeam',
            'playerSpirit.player.positions',
            'playerSpirit.player.team'
        ]));
    }
}
