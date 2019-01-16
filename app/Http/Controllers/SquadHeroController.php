<?php

namespace App\Http\Controllers;

use App\Events\HeroCreated;
use App\Exceptions\GameStartedException;
use App\Hero;
use App\HeroClass;
use App\HeroRace;
use App\HeroRank;
use App\Http\Requests\StoreSquadHero;
use App\Squad;
use Illuminate\Http\Request;

class SquadHeroController extends Controller
{
    public function store(StoreSquadHero $request, $squadUuid)
    {
        $squad = Squad::uuidOrFail($squadUuid);

        $hero = Hero::createWithAttributes([
            'squad_id' => $squad->id,
            'name' => $request->name,
            'hero_class_id' => HeroClass::query()->where('name', '=', $request->class)->first()->id,
            'hero_race_id' => HeroRace::query()->where('name', '=', $request->race)->first()->id,
            'hero_rank_id' => HeroRank::getStarting()->id
        ]);

        // Hooked into for adding slots, measurables...
        event(new HeroCreated($hero));

        return response()->json($hero, 201);
    }
}
