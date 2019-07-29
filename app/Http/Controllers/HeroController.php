<?php

namespace App\Http\Controllers;

use App\Domain\Models\Hero;
use App\Http\Resources\HeroResource;
use Illuminate\Http\Request;

class HeroController extends Controller
{
    public function show($heroSlug)
    {
        // TODO: auth
        $hero = Hero::slugOrFail($heroSlug);
        return new HeroResource($hero->loadMissing([
            'heroClass',
            'heroRace.positions',
            'playerSpirit.game.homeTeam',
            'playerSpirit.game.awayTeam',
            'playerSpirit.player.positions',
            'playerSpirit.player.team'
        ]));
    }
}
