<?php

namespace App\Http\Controllers;

use App\Events\HeroCreated;
use App\Events\SquadCreated;
use App\Hero;
use App\HeroClass;
use App\HeroRace;
use App\HeroRank;
use App\Province;
use App\Squad;
use App\SquadRank;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SquadController extends Controller
{
    public function store(Request $request)
    {

//        /** @var User $user */
//        $user = Auth::user();
//        $squad = $squad->build( $user, $request->name, $request->heroes );
//
//        return response($squad->toJson(), 201);

        /*
         * TODO: Refactor to:
         * 1) Create squad here, Squad::create()
         * 2) Trigger SquadCreated event
         *      2a) Hook into and create Wagon
         *      3a) Trigger Wagon Created event
         *          3a1) Hook into and create Wagon Slots
         * 3) Cycle through $request->heroes and create Hero, Hero::create
         *      3a) Hook into and create Slots
         * 4) Trigger HeroCreated event
         */


        /** @var User $user */
        $user = Auth::user();

        $squad = Squad::generate($user->id, $request->name, $request->heroes);

        return response($squad->toJson(), 201);

//        /** @var Squad $squad */
//        $squad = Squad::create([
//            'user_id' => $user->id,
//            'name' => $request->name,
//            'squad_rank_id' => SquadRank::getStarting()->id,
//            'province_id' => Province::getStarting()->id,
//        ]);
//
//        event(new SquadCreated($squad));
//
//        $heroClasses = HeroClass::all();
//        $heroRaces = HeroRace::all();
//        $startingRank = HeroRank::getStarting();
//
//        foreach($request->heroes as $hero) {
//            /** @var Hero $hero */
//            $hero = Hero::create([
//                'squad_id' => $squad->id,
//                'name' => $hero['name'],
//                'hero_class_id' => $heroClasses->where('name', '=', $hero['class'])->first()->id,
//                'hero_race_id' => $heroRaces->where('name', '=', $hero['race'])->first()->id,
//                'hero_rank_id' => $startingRank->id
//            ]);
//
//            event(new HeroCreated($hero));
//        }
    }
}
