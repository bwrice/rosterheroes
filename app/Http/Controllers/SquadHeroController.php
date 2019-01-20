<?php

namespace App\Http\Controllers;

use App\Events\HeroCreated;
use App\Exceptions\GameStartedException;
use App\Hero;
use App\HeroClass;
use App\Heroes\HeroPosts\HeroPost;
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

        /** @var HeroRace $heroRace */
        $heroRace = HeroRace::query()->where('name', '=', $request->race)->first();

        /** @var HeroPost $heroPost */
        $heroPost = $squad->heroPosts->postFilled(false)->heroRace($heroRace)->first();

        $hero = Hero::createWithAttributes([
            'name' => $request->name,
            'hero_class_id' => HeroClass::query()->where('name', '=', $request->class)->first()->id,
            'hero_rank_id' => HeroRank::getStarting()->id
        ]);

        $heroPost->hero_id = $hero->id;
        $heroPost->save();

        $hero->addStartingSlots();
        $hero->addStartingMeasurables();

        return response()->json($hero, 201);
    }
}
