<?php

namespace App\Http\Controllers;

use App\Domain\Models\Hero;
use App\Domain\Models\Squad;
use App\Http\Resources\HeroResource;
use Illuminate\Http\Request;

class BarracksHeroesController extends Controller
{
    public function __invoke($squadSlug)
    {
        $squad = Squad::slugOrFail($squadSlug);
        $this->authorize(Squad::MANAGE_AUTHORIZATION, $squad);
        $heroes = Hero::query()->amongSquad($squad)->get();
        $heroes->load([
            'measurables.measurableType'
        ]);
        return HeroResource::collection($heroes);
    }
}
