<?php

namespace App\Http\Controllers;

use App\Domain\Models\Hero;
use App\Domain\Models\Squad;
use App\Http\Resources\BarracksHeroResource;
use App\Http\Resources\HeroResource;
use App\Policies\SquadPolicy;
use Illuminate\Http\Request;

class BarracksHeroesController extends Controller
{
    public function __invoke($squadSlug)
    {
        $squad = Squad::findSlugOrFail($squadSlug);
        $this->authorize(SquadPolicy::MANAGE, $squad);
        $heroes = Hero::query()->amongSquad($squad)->get();
        $heroes->load([
            'heroRace',
            'heroClass',
            'playerSpirit.player',
            'measurables.measurableType',
            'slots.item.itemType',
        ]);
        return BarracksHeroResource::collection($heroes);
    }
}
