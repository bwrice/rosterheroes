<?php

namespace App\Http\Controllers;

use App\Events\HeroCreated;
use App\Exceptions\GameStartedException;
use App\Exceptions\HeroPostNotFoundException;
use App\Hero;
use App\HeroClass;
use App\Heroes\HeroPosts\HeroPost;
use App\HeroRace;
use App\HeroRank;
use App\Http\Requests\StoreSquadHero;
use App\Squad;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SquadHeroController extends Controller
{
    public function store(Request $request, $squadUuid)
    {
        $squad = Squad::uuidOrFail($squadUuid);
        $this->authorize(Squad::MANAGE_AUTHORIZATION, $squad);

        $this->validate($request, [
            'name' => 'required|alpha_dash|between:4,20',
            'race' => 'required|exists:hero_races,name',
            'class' => 'required|exists:hero_classes,name'
        ]);

        /** @var HeroRace $heroRace */
        $heroRace = HeroRace::query()->where('name', '=', $request->race)->first();
        /** @var HeroClass $heroClass */
        $heroClass = HeroClass::query()->where('name', '=', $request->class)->first();

        try {
            $hero = $squad->addHero($heroRace, $heroClass, $request->name);
            return response()->json($hero->fresh(), 201);
        } catch ( HeroPostNotFoundException $exception ) {
            throw ValidationException::withMessages([
                'race' => $squad->name . ' does not have a hero post available for hero race: ' . $heroRace->name
            ]);
        }
    }
}
