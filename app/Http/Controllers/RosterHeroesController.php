<?php

namespace App\Http\Controllers;

use App\Domain\Models\Hero;
use App\Domain\Models\Squad;
use App\Http\Resources\SquadCreationHeroResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;

class RosterHeroesController extends Controller
{
    public function __invoke($squadSlug)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);
        $heroes = Hero::query()->amongSquad($squad)->get();
        $heroes->load([
            'heroRace.positions',
            'heroClass',
            'playerSpirit.game.homeTeam',
            'playerSpirit.game.awayTeam',
            'playerSpirit.player.team',
            'playerSpirit.player.positions'
        ]);
        return SquadCreationHeroResource::collection($heroes);
    }
}
